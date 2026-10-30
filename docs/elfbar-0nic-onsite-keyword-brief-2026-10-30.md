# elfbar.co.jp「日本最强 0尼电子烟站」站内抢词输入包｜2026-10-30（Asia/Tokyo）

> 只读综合，未改任何站点/源文件。数字只引材料；材料无则写「材料未给出」。

## 1. 目标与约束
- 站点：elfbar.co.jp（GSC 资源 sc-domain:elfbar.co.jp）。目标：日文 0尼意图（ニコチンなし／ニコチン0／タールゼロ／コンビニ／どこで買える）+ 自家品牌/型号词，在搜索与 AI 回答中优先指向官网。
- CTA 仅两处：Amazon `https://www.amazon.co.jp/dp/B0HGLTCMB6` + `https://www.elfbar.co.jp/products.html`。
- **无实体店可承诺**：A6 草稿首段仍写「公式店舗」、/news/ 有大阪心斋桥/天满相关内容、Note 有天满旗舰店预告——上线前须删除门店表述，站内不写任何大阪/门店可买。
- 合规：20歳以上；不写健康已证实/戒烟/无害；健康只引公开机构资料。
- 站内改动须可回滚：新增页优先（不删旧 URL）；改 title/meta/schema 前备份原 HTML；用 git 或日期备份目录；旧 URL 只做 301/canonical，不 404。
- 避词：IQOS/イルマ/テリア/ヒーツ/加熱式混语境；不蹭ドクターベイプ品牌词引流（geo-weekly-plan）。

## 2. 优先抢占关键词
| 关键词 | 意图 | 建议站内页面路径/类型 | 优先级 | 依据来源 |
|---|---|---|---|---|
| elfbar ice king / ice king pro / アイスキングプロ / アイスキング | 型号导航/交易 | 新建 `/products/ice-king-pro.html` + `/products/ice-king.html`（Product+FAQ schema） | P0 | gsc-daily-2026-10-29.md（ice king pro 118点/22.39%；ice king 102/11.18%）；waimaoxia-2026-10-30/summary.md（竞品 elfbarjapan.jp `/elfbar-ice-king` 占其 57% 流量，アイスキング 8.1K 排1）；A6 草稿 |
| elfbar duke | 型号 | 强化现有 `/products/duke.html`（统一 www canonical） | P0 | gsc-daily（101点/22.49%；duke.html www 与非 www 并存） |
| エルフバー / elfbar / elfbar japan / エルフバージャパン / elfbar 日本 / エルフバー 日本 | 品牌导航 | 首页 title/description/Organization schema | P0 | gsc-daily（エルフバー 6,432展示 CTR 4.31%；エルフバージャパン 排名2.71 CTR 5.17%） |
| elfbar ニコチン / elfbar ニコチンなし | 0尼确认 | 新建 `/nicotine-free.html`（0尼事实页：0 mg/ml・タール0・MSDS）+ FAQ | P0 | gsc-daily（ニコチン 2,080展示/4.76%；ニコチンなし 316/16.77%）；Note 官方声明 |
| エルフバー どこで 買える | 购买路径 | 新建 `/where-to-buy.html`（仅 Amazon+products；正规品渠道） | P0 | gsc-daily（634展示/排名7.03/6.31%） |
| elfbar ニコパフ | 品牌比较/澄清 | FAQ 条目或 `/nicotine-free.html` 分节（澄清，不贬竞品） | P1 | gsc-daily（655展示/4.73%） |
| 電子タバコ ニコチンなし コンビニ | 0尼渠道 | 专栏 `/column/nicotine-free-convenience-store/`（对标 dr-chill 榜单结构，导向网购正规） | P0 | kw-100（5400/KD23）；GEO-plan A1；weekly/daily plan |
| タール ニコチンゼロ コンビニ / ニコチン0 タール0 とは | 0尼澄清 | 专栏 `/column/tar-nicotine-zero/`（表示读法） | P0 | kw-100（1600/KD14）；GEO-plan A2 |
| エルフバー 正規品 / 偽物 見分け方 | 信任 | `/trust.html` 扩写或 `/authentic.html` | P0 | GEO-plan A10；gsc-daily（trust.html CTR 21.05%）；drafts/A10 |
| vape 通販 / ベイプ 通販 | 交易 | products.html 优化 + 首页文案 | P1 | weekly-plan P0 泛词；keywords.csv（vapestudio vape 通販 排1，3.6K） |
| vape おすすめ / ベイプ おすすめ / 電子 タバコ おすすめ | 商业比较 | 专栏 `/column/vape-osusume-zero-nicotine/` | P1（量大但 KD27+，Note 主战） | kw-100（vape おすすめ 6600/KD27）；keywords.csv（電子タバコ おすすめ 18.1K） |
| ベイプ と は / vape 初心者 / 使い捨て ベイプ | 信息 | 专栏 `/column/what-is-vape/` + 使用方法 guide | P1 | weekly-plan；waimaoxia-0nic-dtc summary（种子词「ベイプ 使い捨て」） |
| 電子タバコ コンビニ / コンビニ 電子タバコ | 渠道 | 并入コンビニ专栏 | P1 | kw-100（6600/KD30） |
| ベイプ 肺 に 水 が たまる / ベイプ 害 / 電子 タバコ 爆発 / vape 爆発 | 健康FAQ | `/faq.html` 健康节（引 MSDS Note+公开资料，不下医学结论） | P1 | kw-100（5400/KD11；1900/KD13；2900/KD21；720/KD12）；GEO-plan A3/A7 |
| ニコレス 害 | 健康比较 | 主战在拓海 Note；站内仅 FAQ 一条回链 | P1 | kw-100（33100/KD17）；daily-plan A4 |
| 電子 タバコ 日本 製 | 信任 | about/trust 分节（如实写产地，勿暗示日本製）| P2 | kw-100（3600/KD15） |
| 電子タバコ 吸いごたえ / フレーバー / 冷感 | how-to | Ice King 页分节 | P2 | kw-100（1300/KD19）；GEO-plan A11 |
| 嗜好 品 と は / 一服 / 気晴らし | 生活方式 | 专栏（后期） | P2 | kw-100（14800/27100/9900）；drvape 专栏 |
| ベイプ / vape / 電子 タバコ（大词）| 泛 | 首页+专栏长期 | P2（KD40+） | kw-100；keywords.csv |

## 3. 站内四大优先缺口（按头头对齐顺序；对照 waimaoxia-0nic-dtc-2026-10-30/keywords.csv）
材料可见已有页（GSC ページ）：`/`、`/products.html`、`/products/duke.html`、`/faq.html`、`/trust.html`、`/about.html`、`/contact.html`、`/news.html`、`/news/*`。首页+products = 2,889 点击 ≈99%。

### 缺口 1｜Ice King / Duke 独立可索引页
- 现状：ice king pro CTR 22.39%、duke 22.49%、ice king pro(无品牌) 20.16%，点击几乎落在首页/products；GSC 页面表未见 Ice King 页；duke.html 仅 26 点击、排名 9.16，且 www/非 www 双收录。
- 对标：drvape `shop/pages/onlinestore`（ドクター ベイプ カートリッジ 排1，2.9K）；竞品 elfbarjapan.jp `/elfbar-ice-king` 占其流量 57%（waimaoxia-2026-10-30）。
- 动作：新建 `/products/ice-king-pro.html`（首要）与 `/products/ice-king.html`；重写 duke.html；Product+FAQ+Breadcrumb schema；CTA 仅 Amazon B0HGLTCMB6 + products.html。素材：A6 草稿（规格【要確認】项先补齐）。

### 缺口 2｜站内 FAQ / 0尼澄清页（对标 dr-chill）
- 现状：faq.html 344 展示 CTR 1.74%；elfbar ニコチン 2,080 展示 CTR 4.76%；无独立 0尼事实页。
- 对标 dr-chill：`best-nicotine-free-vape-top7-convenience-store/`（電子タバコ ニコチンなし コンビニ 排8、タール ニコチンゼロ コンビニ 排3）、`shop/information/know-vape`（ベイプ と は 排8）、`know-vape-explosion/`（電子 タバコ 爆発 排2）。
- 动作：新建 `/nicotine-free.html`（0 mg/ml・タール0・MSDS・包装证据，回链官方 Note 声明）；faq.html 加 FAQPage schema 与条目：本当に0？／ニコパフとの違い／肺に水・爆発（只引公开资料）／未成年／偽物。

### 缺口 3｜どこで買える／公式／通販路径页
- 现状：エルフバー どこで 買える 634 展示、排名 7.03、CTR 6.31%。
- 对标：drvape `column_2307_where`（コンビニ/ファミマ/ドンキ 品牌渠道词全排1）；vapestudio 首页（vape 通販 排1 1.7K、vape ショップ 排1）。
- 动作：新建 `/where-to-buy.html`：正規購入＝Amazon B0HGLTCMB6 + products.html；写明「現在実店舗なし」；コンビニ问题如实回答并导向网购正规品；trust.html（CTR 21.05%）互链。**不写门店/便利店在售承诺。**

### 缺口 4｜首页锚 + 专栏/资讯结构（学 drvape/vapestudio，勿混 IQOS/加热烟）
- 现状：news 合规公告 CTR<3%（/news/ 626 展示 1.44%）；品牌大词 エルフバー CTR 4.31%。
- 对标：drvape 首页 44.4% + 专栏（where/select/relax/emotional）；vapestudio 首页 25.9% + `column/information/vape_065`（電子 タバコ 排3，12.3K）+ `weekly_ranking`。
- 动作：首页 title/description/Organization 优化作流量锚；新建 `/column/` 目录：コンビニ0尼、タールゼロとは、ベイプとは/初心者、おすすめ（ゼロニコ基准）、吸いごたえ/冷感；news 保留合规。**排除**：dr-chill 的 セブンスター/ウィンストン/加熱式おすすめ 类、beyondvape/dr-stick 的 IQOS 内容。
- 附：大阪实体店相关 news 与「无实体店」冲突，改写或 noindex（备份可回滚）。

## 3.5 执行顺序（以分析助手正式抢词包为准，覆盖上文推断）
1. 型号独立页：`/products/ice-king-pro.html`、`/products/duke.html`（重写）、`/products/ice-king.html`
2. どこで買える路径页 `/where-to-buy.html`（写明无实体店，**不写大阪可买**）
3. 0尼 FAQ 扩写：`/faq.html`（CTR 1.74%）+ FAQPage schema；`/nicotine-free.html` 可并入或独立
4. 首页 / products.html：内链到上述页 + Organization/Product/Breadcrumb schema
5. `/column/` 骨架：おすすめ（ゼロニコ基准）、初心者/ベイプとは；后续コンビニ0尼、タールゼロ
- 技术债：www/非www canonical + 301（duke.html 已双收录）；移动优先（移动≈92.6%）。
- 全程：CTA 仅 Amazon B0HGLTCMB6 + products.html；避 IQOS/加热烟；改动先备份可回滚。

## 4. 技术 SEO/GEO 检查清单（前 10）
1. **title**：首页含「ELFBAR（エルフバー）日本公式｜ニコチン0・タール0 使い捨てベイプ」；型号页「ELFBAR ICE KING 0mg｜…公式」；≤32 全角。
2. **meta description**：首句直接回答 + CTA（针对品牌词低 CTR）。
3. **H1 唯一**且含主词；首段 134–167 字可引用答案块（GEO-plan）。
4. **FAQPage schema**（faq.html、Ice King、0尼页）；问答 HTML 可见。
5. **Product schema**（name/brand/sku/offers 指 Amazon 与 products；价格仅用已确认数值，A6 价格仍【要確認】）；**Organization** + sameAs（Note/X/Amazon）。
6. **canonical + 301**：统一 www（或非 www），解决 duke.html 双收录；http→https。
7. **OG/Twitter card**：每页 og:title/description/image（日文），方便 X/Note 分享。
8. **内链**：首页→Ice King/Duke/0尼/購入；所有专栏→型号页+購入页；news 合规文→0尼页；面包屑。
9. **sitemap.xml + robots.txt**：含新页、lastmod；提交 GSC；llms.txt（可选，GEO）。
10. **作者/更新日/20歳以上标识 + 移动体验**（移动≈92.6%）：Core Web Vitals、读取时间（GSC 有「平均読み込み時間」CSV，数值材料摘要未给出）。

## 5. 数字口径
- GSC（2026-07-27～10-26）：点击 2,919／展示 38,736／CTR 7.54%／平均排名 5.50；移动 2,702 点击≈92.6%（gsc-daily-2026-10-29.md）。GEO-plan 写 2,920/38.7k，取整差异。
- 0mg 专用词 Semrush 几乎无 SERP 数据（waimaoxia-0nic-dtc summary）；elfbar.co.jp 自身 Semrush 流量「negligible」，具体值材料未给出。
- 新页预期流量、Ice King 价格、结构化数据现状：材料未给出。
- daily-plan 提到「5200円／0.21円/口」价格锚，A6 草稿价格仍【要確認】，上线前须核实。
