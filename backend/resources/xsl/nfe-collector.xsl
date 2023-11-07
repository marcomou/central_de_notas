<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:nfe="http://www.portalfiscal.inf.br/nfe"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="text" doctype-public="XSLT-compat" omit-xml-declaration="yes" encoding="UTF-8" indent="yes"></xsl:output>

  <xsl:include href="nfe-ide.xsl"/>
  <xsl:include href="nfe-emit-dest.xsl"/>
  <xsl:include href="nfe-prod.xsl"/>
  <xsl:include href="nfe-totals.xsl"/>

  <xsl:template match="//nfe:NFe">
      <xsl:text>{"doc":</xsl:text>
        <xsl:text>{"nfe":</xsl:text>
          <xsl:apply-templates mode="NfeElem" select="/"/>
        <xsl:text>}</xsl:text>
        <xsl:if test="//nfe:nfeProc/nfe:protNFe">
          <xsl:text>,</xsl:text>
          <xsl:text>"protocol":</xsl:text>
            <xsl:apply-templates mode="protNFe" select="//nfe:nfeProc/nfe:protNFe/nfe:infProt"/>
            <xsl:text></xsl:text>
        </xsl:if>
      <xsl:text>}</xsl:text>
  </xsl:template>

  <xsl:template match="//nfe:nfeProc">
      <xsl:text>{"doc":</xsl:text>
        <xsl:text>{"nfe":</xsl:text>
          <xsl:apply-templates mode="NfeElem" select="nfe:NFe"/>
        <xsl:text>}</xsl:text>
        <xsl:if test="nfe:protNFe">
          <xsl:text>,</xsl:text>
          <xsl:text>"protocol":</xsl:text>
            <xsl:apply-templates mode="protNFe" select="nfe:protNFe/nfe:infProt"/>
            <xsl:text></xsl:text>
        </xsl:if>
      <xsl:text>}</xsl:text>
  </xsl:template>

  <xsl:template match="//nfe:enviNFe">
      <xsl:text>{"doc":</xsl:text>
        <xsl:text>{"nfe":</xsl:text>
          <xsl:apply-templates mode="NfeElem" select="nfe:NFe"/>
        <xsl:text>}</xsl:text>
        <xsl:if test="nfe:protNFe">
          <xsl:text>,</xsl:text>
          <xsl:text>"protocol":</xsl:text>
            <xsl:apply-templates mode="protNFe" select="nfe:protNFe/nfe:infProt"/>
            <xsl:text></xsl:text>
        </xsl:if>
      <xsl:text>}</xsl:text>
  </xsl:template>

  <xsl:template mode="protNFe" match="nfe:infProt">
    <xsl:text>{</xsl:text>
      <xsl:choose>
        <xsl:when test="nfe:tpAmb and not(nfe:tpAmb='')">
          <xsl:text>"tp_amb":"</xsl:text><xsl:value-of select="nfe:tpAmb"/><xsl:text>",</xsl:text>
        </xsl:when>
        <xsl:otherwise>
          <xsl:text>"tp_amb":"</xsl:text>
            <xsl:value-of select="/nfe:nfeProc/nfe:NFe/nfe:infNFe/nfe:ide/nfe:tpAmb"/>
          <xsl:text>",</xsl:text>
        </xsl:otherwise>
      </xsl:choose>
      <xsl:text>"ch_nfe":"</xsl:text>
        <xsl:value-of select="nfe:chNFe"/>
      <xsl:text>",</xsl:text>
      <xsl:text>"n_prot":"</xsl:text>
        <xsl:value-of select="nfe:nProt"/>
      <xsl:text>",</xsl:text>
      <xsl:text>"x_motivo":"</xsl:text>
        <xsl:value-of select="nfe:xMotivo"/>
      <xsl:text>",</xsl:text>
      <xsl:text>"c_stat":"</xsl:text>
        <xsl:value-of select="nfe:cStat"/>
      <xsl:text>",</xsl:text>
      <xsl:text>"dh_recbto":"</xsl:text>
        <xsl:value-of select="nfe:dhRecbto"/>
      <xsl:text>",</xsl:text>
      <xsl:text>"dig_val":"</xsl:text>
        <xsl:value-of select="nfe:digVal"/>
      <xsl:text>"</xsl:text>
    <xsl:text>}</xsl:text>
  </xsl:template>

  <xsl:template mode="NfeElem" match="nfe:NFe">
      <xsl:text>{</xsl:text>
        <xsl:text>"access_key":</xsl:text>"<xsl:value-of select="substring(//nfe:infNFe/@Id,4)"/><xsl:text>",</xsl:text>
        <xsl:apply-templates mode="infNFeElem" select="nfe:infNFe"/>
      <xsl:text>}</xsl:text>
  </xsl:template>

  <xsl:template mode="infNFeElem" match="nfe:infNFe">
      <xsl:text>"info":</xsl:text><xsl:apply-templates mode="ideElem" select="nfe:ide"/>
      <xsl:text>,</xsl:text>
      <xsl:text>"issuer":</xsl:text><xsl:apply-templates mode="emitElem" select="nfe:emit"/>
      <xsl:text>,</xsl:text>
      <xsl:text>"recipient":</xsl:text>
      <xsl:apply-templates mode="destElem" select="nfe:dest"/>
      <xsl:text>,</xsl:text>
      <xsl:text>"invoice_items":</xsl:text><xsl:text>[</xsl:text>
        <xsl:for-each select="nfe:det">
          <xsl:text>{</xsl:text>
            <xsl:text>"sequence_invoice_number":"</xsl:text><xsl:value-of select="@nItem"/><xsl:text>",</xsl:text>
            <xsl:apply-templates mode="prodElem" select="nfe:prod"/>
            <xsl:if test="nfe:imposto"><xsl:text>,</xsl:text></xsl:if>
            <xsl:apply-templates mode="impostoElem" select="nfe:imposto"/>
          <xsl:text>}</xsl:text>
          <xsl:if test="position() != last()"><xsl:text>,</xsl:text></xsl:if>
        </xsl:for-each>
      <xsl:text>],</xsl:text>
      <xsl:text>"total":</xsl:text>
      <xsl:apply-templates mode="totalElem" select="nfe:total"/>
  </xsl:template>

  <!-- for event XMLs -->
  <xsl:template match="//nfe:procEventoNFe">
      <xsl:text>{"doc":</xsl:text>
        <xsl:text>{"nfe":</xsl:text>
          <xsl:text>{</xsl:text>
            <xsl:text>"access_key":</xsl:text>"<xsl:value-of select="//nfe:retEvento/nfe:infEvento/nfe:chNFe"/><xsl:text>",</xsl:text>
              <xsl:text>"info":{},</xsl:text>
              <xsl:text>"issuer":{},</xsl:text>
              <xsl:text>"recipient":{},</xsl:text>
              <xsl:text>"items":[],</xsl:text>
              <xsl:text>"total":{}</xsl:text>
          <xsl:text>}</xsl:text>
        <xsl:text>}</xsl:text><xsl:text>,</xsl:text>
        <xsl:text>"protocol":</xsl:text>
          <xsl:text>{</xsl:text>
            <xsl:text>"tp_amb":"</xsl:text>
              <xsl:value-of select="//nfe:retEvento/nfe:infEvento/nfe:tpAmb"/>
            <xsl:text>",</xsl:text>
            <xsl:text>"ch_nfe":"</xsl:text>
              <xsl:value-of select="//nfe:retEvento/nfe:infEvento/nfe:chNFe"/>
            <xsl:text>",</xsl:text>
            <xsl:text>"n_prot":"</xsl:text>
              <xsl:value-of select="//nfe:retEvento/nfe:infEvento/nfe:nProt"/>
            <xsl:text>",</xsl:text>
            <xsl:text>"x_motivo":"</xsl:text>
              <xsl:value-of select="//nfe:evento/nfe:infEvento/nfe:detEvento/nfe:xJust"/>
            <xsl:text>",</xsl:text>
            <xsl:text>"c_stat":"</xsl:text>
              <xsl:value-of select="//nfe:retEvento/nfe:infEvento/nfe:cStat"/>
            <xsl:text>",</xsl:text>
            <xsl:text>"dh_recbto":"</xsl:text>
              <xsl:value-of select="//nfe:retEvento/nfe:infEvento/nfe:dhRegEvento"/>
            <xsl:text>","dig_val":""</xsl:text>
          <xsl:text>}</xsl:text>
      <xsl:text>}</xsl:text>
  </xsl:template>

</xsl:stylesheet>