# ELFBAR.jp P0 目标 URL + H1 + 内链（定稿 2026-10-30）

来源：分析助手。执行序 ①–④。

## 全局约束
- CTA 仅：Amazon ASIN B0HGLTCMB6 + 站内 /products.html（及对应产品页）
- 无实体店；禁止大阪旗舰店等「可买」表述
- 避 IQOS / イルマ / テリア / ヒーツ / 加熱式タバコ / ニコパフ / 对手品牌蹭流
- 0尼：ニコチン・タールゼロ / 0mg
- 域名统一 https://elfbar.co.jp（www/非www 按现有 canonical）

## ① 型号独立页
| URL | H1 | 主打词 | 必含内链 |
|---|---|---|---|
| /products/ice-king-pro.html | ELFBAR Ice King Pro｜ニコチン・タールゼロの使い捨てベイプ | elfbar ice king pro / ice king pro / アイスキングプロ | products.html, duke, where-to-buy, FAQ, Amazon CTA |
| /products/duke.html | ELFBAR Duke｜0mg 使い捨て電子タバコ | elfbar duke | products.html, ice-king-pro, where-to-buy, FAQ, CTA |
| /products/ice-king.html | ELFBAR Ice King｜フレーバーとスペック | elfbar ice king | ice-king-pro, products.html, where-to-buy, CTA |

## ② どこで買える
| URL | H1 | 主打词 |
|---|---|---|
| /where-to-buy.html | エルフバーはどこで買える？公式の買い方（通販） | エルフバー どこで 買える / elfbar 公式 / vape 通販 |

明示実店舗なし；勿写コンビニ可买。

## ③ 0尼 FAQ
| URL | H1 |
|---|---|
| /faq.html（重写） | ELFBARはニコチンなし？0mg・タールゼロのよくある質問 |

FAQ schema；链 products / 型号 / where-to-buy。

## ④ 首页 + products
- `/`：0尼一句 + 型号入口；Organization + FAQ 摘要；CTA 两处
- `/products.html`：每型号链独立页；ItemList/Product schema

## 执行顺序
1 Ice King Pro + Duke → 2 where-to-buy → 3 FAQ → 4 首页/products 内链 schema
