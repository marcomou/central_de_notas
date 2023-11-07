<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:nfe="http://www.portalfiscal.inf.br/nfe"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

  <xsl:template mode="emitElem" match="nfe:emit">
      <xsl:text>{"name":"</xsl:text><xsl:value-of select="nfe:xNome"/><xsl:text>",</xsl:text>
    <xsl:choose><xsl:when test="nfe:CNPJ">
      <xsl:text>"federal_taxid":"</xsl:text><xsl:value-of select="nfe:CNPJ"/><xsl:text>",</xsl:text>
      <xsl:text>"state_taxid":"</xsl:text><xsl:value-of select="nfe:IE"/><xsl:text>",</xsl:text>
      <xsl:text>"fantasy_name":"</xsl:text><xsl:value-of select="nfe:xFant"/><xsl:text>",</xsl:text>
      </xsl:when>
      <xsl:otherwise>
        <xsl:text>"federal_taxid":"</xsl:text><xsl:value-of select="nfe:CPF"/><xsl:text>",</xsl:text>
      </xsl:otherwise>
    </xsl:choose>
    <xsl:text>"cnae":"</xsl:text><xsl:value-of select="nfe:CNAE"/><xsl:text>"</xsl:text>
    <xsl:if test="nfe:enderEmit"><xsl:text>,</xsl:text><xsl:apply-templates mode="enderElem" select="nfe:enderEmit"/></xsl:if>
    <xsl:text>}</xsl:text>
  </xsl:template>

  <xsl:template mode="destElem" match="nfe:dest">
    <xsl:text>{"name":"</xsl:text><xsl:value-of select="nfe:xNome"/><xsl:text>",</xsl:text>
    <xsl:choose>
      <xsl:when test="nfe:CNPJ">
        <xsl:text>"federal_taxid":"</xsl:text><xsl:value-of select="nfe:CNPJ"/><xsl:text>",</xsl:text>
        <xsl:text>"state_taxid":"</xsl:text><xsl:value-of select="nfe:IE"/><xsl:text>",</xsl:text>
        <xsl:text>"fantasy_name":"</xsl:text><xsl:value-of select="nfe:xFant"/><xsl:text>",</xsl:text>
      </xsl:when>
      <xsl:otherwise>
        <xsl:choose>
          <xsl:when test="nfe:idEstrangeiro and not(nfe:idEstrangeiro='')">
            <xsl:text>"federal_taxid":"</xsl:text><xsl:value-of select="nfe:idEstrangeiro"/><xsl:text>",</xsl:text>
            <xsl:text>"fantasy_name":"</xsl:text><xsl:value-of select="nfe:xNome"/><xsl:text>",</xsl:text>
          </xsl:when>
          <xsl:otherwise>
            <xsl:text>"fantasy_name":"",</xsl:text>
            <xsl:text>"federal_taxid":"</xsl:text><xsl:value-of select="nfe:CPF"/><xsl:text>",</xsl:text>
          </xsl:otherwise>
        </xsl:choose>
      </xsl:otherwise>
    </xsl:choose>
    <xsl:text>"cnae":"</xsl:text><xsl:value-of select="nfe:CNAE"/><xsl:text>"</xsl:text>
    <xsl:if test="nfe:enderDest"><xsl:text>,</xsl:text><xsl:apply-templates mode="enderElem" select="nfe:enderDest"/></xsl:if>
    <xsl:text>}</xsl:text>
  </xsl:template>

  <xsl:template mode="enderElem" match="nfe:enderDest|nfe:enderEmit">
    <xsl:text>"address_street":"</xsl:text><xsl:value-of select="nfe:xLgr"/><xsl:text>",</xsl:text>
    <xsl:text>"address_number":"</xsl:text><xsl:value-of select="nfe:nro"/><xsl:text>",</xsl:text>
    <xsl:text>"address_district":"</xsl:text><xsl:value-of select="nfe:xBairro"/><xsl:text>",</xsl:text>
    <xsl:text>"address_city_code":"</xsl:text><xsl:value-of select="nfe:cMun"/><xsl:text>",</xsl:text>
    <xsl:text>"address_city_name":"</xsl:text><xsl:value-of select="nfe:xMun"/><xsl:text>",</xsl:text>
    <xsl:text>"address_state":"</xsl:text><xsl:value-of select="nfe:UF"/><xsl:text>",</xsl:text>
    <xsl:text>"address_postal_code":"</xsl:text><xsl:value-of select="nfe:CEP"/><xsl:text>",</xsl:text>
    <xsl:text>"address_country_code":"</xsl:text><xsl:value-of select="nfe:cPais"/><xsl:text>",</xsl:text>
    <xsl:text>"address_country_name":"</xsl:text><xsl:value-of select="nfe:xPais"/><xsl:text>",</xsl:text>
    <xsl:text>"address_phone_number":"</xsl:text><xsl:value-of select="nfe:fone"/><xsl:text>"</xsl:text>
  </xsl:template>
</xsl:stylesheet>