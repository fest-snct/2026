#!/bin/bash
if ! command -v cwebp > /dev/null 2>&1; then
    echo "\"cwebp\" が見つかりません"
    echo "UbuntuまたはDebianを使用している場合は sudo apt install -y webp でインストールできます"
    exit 1
fi
find . -type f \( -iname "*.jpg" -o -iname "*.png" -o -iname "*.jpeg" \) -print0 | while IFS= read -r -d '' File; do
    
    # cwebpで変換し、&& そのコマンドが成功した場合のみ、元のファイルをrmで削除する
    cwebp -metadata icc -sharp_yuv "$File" -o "${File%.*}.webp" # && rm "$File"

done

echo "変換とオリジナルファイルの削除が完了しました。"
