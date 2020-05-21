<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"> 

<xsl:output method="xml" indent="yes" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"/>
    <xsl:template match="/">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
            <link rel="stylesheet" href="./dizajn.css" type="text/css"/>
            <title>XML Podaci</title>
        </head>
        <body>
            <div id="main-cnt">
                <header id="header">
                    <div id="h1-div">
                        <h1 id="main-title">Najviši neboderi svijeta</h1>
                    </div>
                    <div id="img-div" role="button" onclick="location.href='index.html'"></div>
                </header>
                <div id="main-inner-cnt">
                    <div id="left-inner-cnt">
                        <nav id="nav">
                            <ul id="list-cnt">
                                <li class="list"><a href="index.html">Početna</a></li>
                                <li class="list"><a href="obrazac.html">Pretraživanje</a></li>
                                <li class="list"><a href="http://www.fer.unizg.hr/predmet/or">Predmet OR</a></li>
                                <li class="list"><a href="http://www.fer.unizg.hr/" target="_blank">Sjedište FER-a</a></li>
                                <li class="list"><a href="mailto:mm50180@fer.hr">Mail autoru</a></li>
                                <li class="list"><a href="podaci.xml">XML podaci</a></li>
                            </ul>
                        </nav>
                    </div>
                    <div id="right-inner-cnt" class="xml-table-cnt">
                        <table id="table" class="xml-table">
                            <tr>
                                <!-- koristenje funkcije position() je nepotrebno ispod, ali to koristim u svrhu ucenja -->
                                <th><xsl:value-of select="name(/kolekcija-nebodera/*[position() = 1]/naziv/*[position() = 1])"/></th>
                                <th><xsl:value-of select="name(/kolekcija-nebodera/*[position() = 1]/adresa/*[position() = 2])"/></th>
                                <th><xsl:value-of select="name(/kolekcija-nebodera/*[position() = 1]/gradnja/*[position() = 2])"/></th>
                                <th><xsl:value-of select="name(/kolekcija-nebodera/*[position() = 1]/*[position() = 4])"/></th>
                                <th><xsl:value-of select="name(/kolekcija-nebodera/*[position() = 1]/*[position() = 6])"/></th>
                                <th><xsl:value-of select="name(/kolekcija-nebodera/*[position() = 1]/*[position() = 8])"/></th>                     
                            </tr>
                            <xsl:for-each select="kolekcija-nebodera/neboder">
                                <tr class="rows">
                                    <td><xsl:value-of select="naziv/osnovni-naziv/text()"/></td>
                                    <td><xsl:value-of select="adresa/mjesto/text()"/></td>
                                    <td><xsl:value-of select="gradnja/status/text()"/></td>

                                    <!-- postoji li cijena -->
                                    <xsl:if test="cijena">    
                                        <td><xsl:value-of select="cijena"/></td>
                                    </xsl:if>
                                    <xsl:if test="not(cijena)">    
                                        <td>N/A</td>
                                    </xsl:if>
                                    
                                    <td><xsl:value-of select="visina"/></td>
                                    
                                    <!-- ako ima vise od jednog arhitekta, napravi disable-ani dropdown -->
                                    <xsl:choose>
                                        <xsl:when test="count(arhitekt) &gt; 1">
                                            <td>
                                                <select>
                                                    <xsl:for-each select="arhitekt">
                                                        <option disabled="disabled">
                                                            <xsl:attribute name="disabled">disabled</xsl:attribute>                                                           
                                                            <xsl:attribute name="value">
                                                                <xsl:value-of select="text()"/>
                                                            </xsl:attribute>
                                                            <xsl:value-of select="text()"/>
                                                        </option>
                                                    </xsl:for-each>
                                                </select>
                                            </td>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <td><xsl:value-of select="arhitekt"/></td>
                                        </xsl:otherwise>
                                    </xsl:choose>
                                </tr>
                            </xsl:for-each>
                        </table>
                        <div id="footer" class="xml-table-footer">
                            <label>Autor:</label><strong>Moris Može</strong>
                            <p id="about">Web sjedište nekoliko najviših nebodera na svijetu.</p>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>
    </xsl:template>
</xsl:stylesheet>