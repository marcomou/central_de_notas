<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:nfe="http://www.portalfiscal.inf.br/nfe"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

  <xsl:template mode="totalElem" match="nfe:total">
    <xsl:text>{</xsl:text>
    <xsl:text>"icms_calculation_base":"</xsl:text><xsl:value-of select="nfe:ICMSTot/nfe:vBC"/><xsl:text>",</xsl:text>
    <xsl:text>"icms_total_value":"</xsl:text><xsl:value-of select="nfe:ICMSTot/nfe:vICMS"/><xsl:text>",</xsl:text>

    <xsl:text>"icms_discharged_total_value": </xsl:text>
    <xsl:choose>
     <xsl:when test="nfe:ICMSTot/nfe:vICMSDeson"><xsl:value-of select="nfe:ICMSTot/nfe:vICMSDeson"/></xsl:when>
     <xsl:otherwise>null</xsl:otherwise>
    </xsl:choose>
    <xsl:text>,</xsl:text>

    <xsl:text>"icms_st_calculation_base":"</xsl:text><xsl:value-of select="nfe:ICMSTot/nfe:vBCST"/><xsl:text>",</xsl:text>
    <xsl:text>"icms_st_total_value":"</xsl:text><xsl:value-of select="nfe:ICMSTot/nfe:vST"/><xsl:text>",</xsl:text>
    <xsl:text>"products_total_value":"</xsl:text><xsl:value-of select="nfe:ICMSTot/nfe:vProd"/><xsl:text>",</xsl:text>
    <xsl:text>"freight_charges_total_value":"</xsl:text><xsl:value-of select="nfe:ICMSTot/nfe:vFrete"/><xsl:text>",</xsl:text>
    <xsl:text>"insurance_total_value":"</xsl:text><xsl:value-of select="nfe:ICMSTot/nfe:vSeg"/><xsl:text>",</xsl:text>
    <xsl:text>"discount_total_value":"</xsl:text><xsl:value-of select="nfe:ICMSTot/nfe:vDesc"/><xsl:text>",</xsl:text>
    <xsl:text>"ii_total_value":"</xsl:text><xsl:value-of select="nfe:ICMSTot/nfe:vII"/><xsl:text>",</xsl:text>
    <xsl:text>"ipi_total_value":"</xsl:text><xsl:value-of select="nfe:ICMSTot/nfe:vIPI"/><xsl:text>",</xsl:text>
    <xsl:text>"pis_value":"</xsl:text><xsl:value-of select="nfe:ICMSTot/nfe:vPIS"/><xsl:text>",</xsl:text>
    <xsl:text>"cofins_value":"</xsl:text><xsl:value-of select="nfe:ICMSTot/nfe:vCOFINS"/><xsl:text>",</xsl:text>
    <xsl:text>"other_expenses":"</xsl:text><xsl:value-of select="nfe:ICMSTot/nfe:vOutro"/><xsl:text>",</xsl:text>
    <xsl:text>"invoice_gross_value":"</xsl:text><xsl:value-of select="nfe:ICMSTot/nfe:vNF"/><xsl:text>",</xsl:text>
    <xsl:text>"taxes_total_value":"</xsl:text><xsl:value-of select="nfe:ICMSTot/nfe:vTotTrib"/><xsl:text>"</xsl:text>
    <xsl:text>}</xsl:text>
  </xsl:template>
</xsl:stylesheet>