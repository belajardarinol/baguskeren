#!/usr/bin/env python3
"""
Script to replace all .jpg images in the project with Lorem Picsum placeholder images.
Lorem Picsum provides beautiful placeholder images at https://picsum.photos/
"""

import os
import requests
import time
from pathlib import Path
from PIL import Image
import random

def get_image_dimensions(image_path):
    """Get dimensions of an existing image."""
    try:
        with Image.open(image_path) as img:
            return img.size  # (width, height)
    except Exception as e:
        print(f"Error reading {image_path}: {e}")
        return (800, 600)  # Default dimensions

def download_lorem_picsum_image(width, height, output_path, seed=None):
    """Download a Lorem Picsum image with specified dimensions."""
    if seed is None:
        seed = random.randint(1, 1000)
    
    url = f"https://picsum.photos/{width}/{height}?random={seed}"
    
    try:
        print(f"Downloading {url} -> {output_path}")
        response = requests.get(url, timeout=30)
        response.raise_for_status()
        
        with open(output_path, 'wb') as f:
            f.write(response.content)
        
        print(f"✅ Successfully downloaded: {output_path}")
        return True
    except Exception as e:
        print(f"❌ Error downloading {url}: {e}")
        return False

def find_jpg_files(root_dir):
    """Find all .jpg files in the project."""
    jpg_files = []
    for root, dirs, files in os.walk(root_dir):
        for file in files:
            if file.lower().endswith('.jpg'):
                jpg_files.append(os.path.join(root, file))
    return jpg_files

def main():
    # Project root directory
    project_root = "/Users/ridho/Documents/GitHub/nuicreativehtml-11"
    
    print("🖼️  Lorem Picsum Image Replacer")
    print("=" * 50)
    
    # Find all .jpg files
    jpg_files = find_jpg_files(project_root)
    print(f"Found {len(jpg_files)} .jpg files to replace")
    
    if not jpg_files:
        print("No .jpg files found!")
        return
    
    # Create backup directory
    backup_dir = os.path.join(project_root, "backup_original_images")
    os.makedirs(backup_dir, exist_ok=True)
    print(f"📁 Backup directory: {backup_dir}")
    
    success_count = 0
    
    for i, jpg_file in enumerate(jpg_files, 1):
        print(f"\n[{i}/{len(jpg_files)}] Processing: {jpg_file}")
        
        # Get original dimensions
        width, height = get_image_dimensions(jpg_file)
        print(f"Original dimensions: {width}x{height}")
        
        # Create backup
        relative_path = os.path.relpath(jpg_file, project_root)
        backup_path = os.path.join(backup_dir, relative_path)
        os.makedirs(os.path.dirname(backup_path), exist_ok=True)
        
        try:
            # Copy original to backup
            import shutil
            shutil.copy2(jpg_file, backup_path)
            print(f"💾 Backed up to: {backup_path}")
        except Exception as e:
            print(f"⚠️  Backup failed: {e}")
        
        # Download new Lorem Picsum image
        seed = random.randint(1, 1000)
        if download_lorem_picsum_image(width, height, jpg_file, seed):
            success_count += 1
        
        # Small delay to be respectful to the API
        time.sleep(0.5)
    
    print("\n" + "=" * 50)
    print(f"🎉 Replacement complete!")
    print(f"✅ Successfully replaced: {success_count}/{len(jpg_files)} images")
    print(f"📁 Original images backed up to: {backup_dir}")
    
    if success_count < len(jpg_files):
        print(f"⚠️  {len(jpg_files) - success_count} images failed to download")

if __name__ == "__main__":
    main()
