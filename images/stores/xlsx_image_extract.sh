#!/bin/bash
# カレントディレクトリの .xlsx から
# xl/media/image1.* を xlsx のファイル名で取り出すスクリプト
# （xlsx は zip 形式なのでリネームせずそのまま unzip で読める）
#
# 使い方: ./extract_xlsx_images.sh

set -u

shopt -s nullglob nocaseglob
xlsx_files=(*.xlsx)
shopt -u nocaseglob

if [ ${#xlsx_files[@]} -eq 0 ]; then
    echo "カレントディレクトリに .xlsx ファイルが見つかりません。"
    exit 1
fi

for xlsx in "${xlsx_files[@]}"; do
    base="${xlsx%.*}"          # 拡張子を除いたファイル名

    echo "処理中: $xlsx"

    # zip内の xl/media/image1.* を探す（拡張子を特定）
    image_entry=$(unzip -Z1 "$xlsx" 2>/dev/null | grep -E '^xl/media/image1\.[A-Za-z0-9]+$' | head -n 1)

    if [ -z "$image_entry" ]; then
        echo " === 警告: $xlsx 内に xl/media/image1.* が見つかりませんでした。"
        continue
    fi

    ext="${image_entry##*.}"
    out_name="${base}.${ext}"

    # xlsxから該当ファイルだけ取り出してカレントディレクトリに配置
    unzip -p "$xlsx" "$image_entry" > "$out_name"

    if [ $? -eq 0 ]; then
        echo "     抽出成功: $image_entry -> $out_name"
    else
        echo " --- エラー: $image_entry の抽出に失敗しました。"
    fi
done

echo "完了しました。"
