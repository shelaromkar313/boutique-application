#!/usr/bin/env python3
"""
CatVTON Virtual Try-On Bridge Script
Official ICLR 2025 Model: "CatVTON: Concatenation Is All You Need for Virtual Try-On with Diffusion Models"
Authors: Zheng Chong, Xiao Dong, Haoxiang Li, Shiyue Zhang, et al.
GitHub: https://github.com/Zheng-Chong/CatVTON
HuggingFace: https://huggingface.co/spaces/zhengchong/CatVTON
"""

import argparse
import base64
import json
import os
import sys
import tempfile
import urllib.request
from PIL import Image
from gradio_client import Client, handle_file


def parse_args():
    parser = argparse.ArgumentParser(description="CatVTON Virtual Try-On Inference Bridge")
    parser.add_argument("--person_image", type=str, required=True, help="Base64 or file path of person image")
    parser.add_argument("--garment_image", type=str, required=True, help="URL or file path of garment image")
    parser.add_argument("--output_path", type=str, required=True, help="Output destination file path (jpg/png/webp)")
    parser.add_argument("--category", type=str, default="upper_body", choices=["upper_body", "lower_body", "dresses", "upper", "lower", "overall"])
    parser.add_argument("--steps", type=int, default=30, help="Inference steps (default 30)")
    parser.add_argument("--guidance_scale", type=float, default=2.5, help="Guidance scale / CFG (default 2.5)")
    parser.add_argument("--seed", type=int, default=42, help="Random seed")
    parser.add_argument("--hf_token", type=str, default="", help="Hugging Face User Access Token")
    parser.add_argument("--show_type", type=str, default="result only", choices=["result only", "input & result", "input & mask & result"])
    return parser.parse_args()


def load_image(img_input: str, temp_dir: str, prefix: str) -> Image.Image:
    """Load image from base64 string, URL, or local path into a PIL RGB Image."""
    if img_input.startswith("data:image/") or ";base64," in img_input:
        base64_data = img_input.split(";base64,")[-1]
        img_bytes = base64.b64decode(base64_data)
        temp_file = os.path.join(temp_dir, f"{prefix}_b64.tmp")
        with open(temp_file, "wb") as f:
            f.write(img_bytes)
        img = Image.open(temp_file)
    elif img_input.startswith("http://") or img_input.startswith("https://"):
        temp_file = os.path.join(temp_dir, f"{prefix}_url.tmp")
        req = urllib.request.Request(
            img_input,
            headers={
                "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0 Safari/537.36",
                "Accept": "image/webp,image/apng,image/*,*/*",
            }
        )
        with urllib.request.urlopen(req, timeout=30) as response, open(temp_file, "wb") as out_f:
            out_f.write(response.read())
        img = Image.open(temp_file)
    elif os.path.exists(img_input):
        img = Image.open(img_input)
    else:
        # Check if it is a raw base64 string without data prefix
        try:
            img_bytes = base64.b64decode(img_input)
            temp_file = os.path.join(temp_dir, f"{prefix}_raw_b64.tmp")
            with open(temp_file, "wb") as f:
                f.write(img_bytes)
            img = Image.open(temp_file)
        except Exception:
            raise FileNotFoundError(f"Could not load image source: {img_input[:80]}")

    return img.convert("RGB")


def main():
    args = parse_args()

    # Map category to CatVTON cloth_type
    cat_map = {
        "upper_body": "upper",
        "upper": "upper",
        "lower_body": "lower",
        "lower": "lower",
        "dresses": "overall",
        "overall": "overall",
    }
    cloth_type = cat_map.get(args.category, "upper")

    token = args.hf_token or os.getenv("HUGGINGFACE_API_KEY") or os.getenv("HF_TOKEN")

    with tempfile.TemporaryDirectory() as temp_dir:
        try:
            # 1. Load person and garment images
            person_pil = load_image(args.person_image, temp_dir, "person")
            garment_pil = load_image(args.garment_image, temp_dir, "garment")

            # 2. Resize and save clean PNG files
            person_png_path = os.path.join(temp_dir, "person_clean.png")
            garment_png_path = os.path.join(temp_dir, "garment_clean.png")
            layer_png_path = os.path.join(temp_dir, "blank_layer.png")

            # Optimal resolution for CatVTON diffusion UNet: 768x1024
            target_w, target_h = 768, 1024
            person_pil = person_pil.resize((target_w, target_h), Image.LANCZOS)
            garment_pil = garment_pil.resize((target_w, target_h), Image.LANCZOS)

            person_pil.save(person_png_path, format="PNG")
            garment_pil.save(garment_png_path, format="PNG")

            # Blank layer image (RGBA) to trigger CatVTON's built-in AutoMasker (DensePose + SCHP)
            blank_layer = Image.new("RGBA", (target_w, target_h), (0, 0, 0, 0))
            blank_layer.save(layer_png_path, format="PNG")

            # 3. Connect to CatVTON Hugging Face Space
            client = Client("zhengchong/CatVTON", token=token if token else None)

            # 4. Construct editor data payload
            editor_data = {
                "background": handle_file(person_png_path),
                "layers": [handle_file(layer_png_path)],
                "composite": handle_file(person_png_path),
            }

            # 5. Submit job to CatVTON inference pipeline
            job = client.submit(
                person_image=editor_data,
                cloth_image=handle_file(garment_png_path),
                cloth_type=cloth_type,
                num_inference_steps=args.steps,
                guidance_scale=args.guidance_scale,
                seed=args.seed,
                show_type=args.show_type,
                api_name="/submit_function",
            )

            result_file = job.result(timeout=180)

            if not result_file or not os.path.exists(result_file):
                raise RuntimeError("CatVTON completed but returned an invalid output path.")

            # 6. Save result image to the target output path
            output_dir = os.path.dirname(os.path.abspath(args.output_path))
            if output_dir and not os.path.exists(output_dir):
                os.makedirs(output_dir, exist_ok=True)

            res_img = Image.open(result_file).convert("RGB")
            
            # Save format based on target file extension
            ext = os.path.splitext(args.output_path)[1].lower()
            if ext in [".jpg", ".jpeg"]:
                res_img.save(args.output_path, format="JPEG", quality=95)
            elif ext == ".webp":
                res_img.save(args.output_path, format="WEBP", quality=95)
            else:
                res_img.save(args.output_path, format="PNG")

            output_result = {
                "success": True,
                "output_path": args.output_path,
                "model": "CatVTON (ICLR 2025)",
                "cloth_type": cloth_type,
                "steps": args.steps,
                "guidance_scale": args.guidance_scale,
            }
            print(json.dumps(output_result))
            sys.exit(0)

        except Exception as e:
            err_result = {
                "success": False,
                "error": str(e),
                "model": "CatVTON (ICLR 2025)",
            }
            print(json.dumps(err_result))
            sys.exit(1)


if __name__ == "__main__":
    main()
