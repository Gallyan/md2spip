<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:s="http://www.sitemaps.org/schemas/sitemap/0.9">
  <xsl:output method="html" encoding="UTF-8" indent="no" doctype-system="about:legacy-compat"/>

  <xsl:template match="/">
    <html lang="fr">
      <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <meta name="robots" content="noindex"/>
        <title>Sitemap — Markdown to SPIP</title>
        <link rel="icon" type="image/svg+xml" href="/favicon.svg"/>
        <style>
          :root { color-scheme: dark; }
          * { box-sizing: border-box; }
          body { font-family: system-ui, -apple-system, sans-serif; color: #f1f5f9; background: #0f172a; margin: 0; line-height: 1.5; }
          header { background: #1e293b; border-bottom: 1px solid #334155; padding: 1rem 1.5rem; display: flex; align-items: center; gap: .75rem; }
          header .brand { font-weight: 600; color: #fff; }
          header .accent { color: #10b981; }
          .wrap { max-width: 1100px; margin: 0 auto; padding: 2rem 1.5rem; }
          h1 { font-size: 2rem; margin: 0 0 .5rem; color: #fff; }
          .lead { color: #cbd5e1; margin-bottom: 2rem; }
          .lead strong { color: #34d399; }
          table { width: 100%; border-collapse: collapse; background: rgba(30, 41, 59, .5); border: 1px solid #334155; border-radius: .5rem; overflow: hidden; }
          th { text-align: left; font-weight: 600; padding: .85rem 1rem; background: #1e293b; border-bottom: 1px solid #334155; font-size: .75rem; text-transform: uppercase; letter-spacing: .08em; color: #94a3b8; }
          td { padding: .85rem 1rem; border-bottom: 1px solid rgba(51, 65, 85, .5); vertical-align: top; font-size: .9rem; }
          tr:last-child td { border-bottom: none; }
          tr:hover td { background: rgba(16, 185, 129, .05); }
          a { color: #34d399; text-decoration: none; border-bottom: 1px solid rgba(52, 211, 153, .3); }
          a:hover { border-bottom-color: #34d399; }
          .meta { color: #94a3b8; font-size: .8rem; white-space: nowrap; }
          .pill { display: inline-block; padding: .15rem .5rem; border-radius: 9999px; background: rgba(16, 185, 129, .15); font-size: .75rem; color: #34d399; }
          @media (max-width: 640px) { .hide-sm { display: none; } }
        </style>
      </head>
      <body>
        <header>
          <a href="/"><span class="brand">Markdown<span class="accent"> → </span>SPIP</span></a>
        </header>
        <div class="wrap">
          <h1>Sitemap</h1>
          <p class="lead">
            <strong><xsl:value-of select="count(s:urlset/s:url)"/></strong> URL référencée(s).
            Cette page est lisible par les moteurs de recherche (XML brut) et par les humains (rendu via XSL).
          </p>
          <table>
            <thead>
              <tr>
                <th>URL</th>
                <th class="hide-sm">Modifié</th>
                <th class="hide-sm">Fréquence</th>
                <th>Priorité</th>
              </tr>
            </thead>
            <tbody>
              <xsl:for-each select="s:urlset/s:url">
                <xsl:sort select="s:priority" order="descending" data-type="number"/>
                <tr>
                  <td><a href="{s:loc}"><xsl:value-of select="s:loc"/></a></td>
                  <td class="meta hide-sm">
                    <xsl:choose>
                      <xsl:when test="s:lastmod"><xsl:value-of select="s:lastmod"/></xsl:when>
                      <xsl:otherwise>—</xsl:otherwise>
                    </xsl:choose>
                  </td>
                  <td class="meta hide-sm"><xsl:value-of select="s:changefreq"/></td>
                  <td><span class="pill"><xsl:value-of select="format-number(s:priority * 100, '0')"/>%</span></td>
                </tr>
              </xsl:for-each>
            </tbody>
          </table>
        </div>
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
