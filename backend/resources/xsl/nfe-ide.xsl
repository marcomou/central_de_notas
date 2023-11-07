<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:nfe="http://www.portalfiscal.inf.br/nfe"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

  <xsl:template mode="ideElem" match="nfe:ide">
    <xsl:text>{</xsl:text>
    <!-- tabela IBGE -->
    <xsl:text>"location_code":"</xsl:text><xsl:value-of select="nfe:cUF"/><xsl:text>",</xsl:text>
    <!-- Utilizar a Tabela do IBGE (Anexo IX - Tabela de UF, Município e País) -->
    <xsl:text>"issuer_city_code":"</xsl:text><xsl:value-of select="nfe:cMunFG"/><xsl:text>",</xsl:text>
    <!-- Código numérico que compõe a Chave de Acesso.  -->
    <!-- Número aleatório gerado pelo emitente para cada  -->
    <!-- NF-e para evitar acessos indevidos da NF-e. (v2.0) -->
    <xsl:text>"invoice_random_number":"</xsl:text><xsl:value-of select="nfe:cNF"/><xsl:text>",</xsl:text>
    <!-- Informar a natureza da operação de que decorrer a saída ou a entrada, tais como:  -->
    <!-- venda, compra, transferência, devolução, importação, consignação, remessa  -->
    <!-- (para fins de demonstração, de industrialização ou outra), -->
    <xsl:text>"operation_nature":"</xsl:text><xsl:value-of select="nfe:natOp"/><xsl:text>",</xsl:text>
    <!-- 0=Pagamento à vista; 1=Pagamento a prazo;  2=Outros. -->
    <xsl:text>"payment_indicator": </xsl:text>
    <xsl:choose>
     <xsl:when test="nfe:indPag"><xsl:value-of select="nfe:indPag"/></xsl:when>
     <xsl:otherwise>null</xsl:otherwise>
    </xsl:choose>
    <xsl:text>,</xsl:text>

    <!-- 55=NF-e emitida em substituição ao modelo 1 ou 1A; -->
    <!-- 65=NFC-e, utilizada nas operações de venda no varejo (a critério da UF aceitar este modelo de documento). -->
    <xsl:text>"fiscal_document_model":"</xsl:text><xsl:value-of select="nfe:mod"/><xsl:text>",</xsl:text>
    <!-- Série do Documento Fiscal, preencher com zeros na hipótese de a NF-e não possuir série. (v2.0) -->
    <!-- Série 890-899: uso exclusivo para emissão de NF-e avulsa, pelo contribuinte com seu certificado digital, através do site do Fisco (procEmi=2). (v2.0) -->
    <!-- Serie 900-999: uso exclusivo de NF-e emitidas no SCAN. (v2.0) -->
    <xsl:text>"fiscal_document_series":"</xsl:text><xsl:value-of select="nfe:serie"/><xsl:text>",</xsl:text>
    <!-- Número do Documento Fiscal, de controle do emissor -->
    <xsl:text>"fiscal_document_number":"</xsl:text><xsl:value-of select="nfe:nNF"/><xsl:text>",</xsl:text>
    <!-- Data e hora no formato UTC (Universal Coordinated Time): AAAA-MM-DDThh:mm:ssTZD -->
    <xsl:text>"issued_at":</xsl:text>
    <xsl:choose>
     <xsl:when test="nfe:dhEmi">"<xsl:value-of select="nfe:dhEmi"/>"</xsl:when>
     <xsl:otherwise>null</xsl:otherwise>
    </xsl:choose>
    <xsl:text>,</xsl:text>
    <!-- 0=Entrada; 1=Saída -->
    <xsl:text>"operation_type":"</xsl:text><xsl:value-of select="nfe:tpNF"/><xsl:text>",</xsl:text>
    <!-- 1=Operação interna; 2=Operação interestadual; 3=Operação com exterior. -->
    <xsl:text>"destiny_identifier": </xsl:text>
    <xsl:choose>
     <xsl:when test="nfe:idDest"><xsl:value-of select="nfe:idDest"/></xsl:when>
     <xsl:otherwise>null</xsl:otherwise>
    </xsl:choose>
    <xsl:text>,</xsl:text>
    <!-- 1=Emissão normal (não em contingência); -->
    <!-- 2=Contingência FS-IA, com impressão do DANFE em formulário de segurança; -->
    <!-- 3=Contingência SCAN (Sistema de Contingência do Ambiente Nacional); -->
    <!-- 4=Contingência DPEC (Declaração Prévia da Emissão em Contingência); -->
    <!-- 5=Contingência FS-DA, com impressão do DANFE em formulário de segurança; -->
    <!-- 6=Contingência SVC-AN (SEFAZ Virtual de Contingência do AN); -->
    <!-- 7=Contingência SVC-RS (SEFAZ Virtual de Contingência do RS); -->
    <xsl:text>"issuing_type":"</xsl:text><xsl:value-of select="nfe:tpEmis"/><xsl:text>",</xsl:text>
    <!-- DV será calculado com a aplicação do algoritmo módulo 11 (base 2,9) da Chave de Acesso. -->
    <xsl:text>"verifying_digit":"</xsl:text><xsl:value-of select="nfe:cDV"/><xsl:text>",</xsl:text>
    <!-- 1=Produção/2=Homologação -->
    <xsl:text>"environmental_type":"</xsl:text><xsl:value-of select="nfe:tpAmb"/><xsl:text>",</xsl:text>
    <!-- 1=NF-e normal; 2=NF-e complementar;  3=NF-e de ajuste; 4=Devolução de mercadoria. -->
    <xsl:text>"issuing_purpose":"</xsl:text><xsl:value-of select="nfe:finNFe"/><xsl:text>",</xsl:text>
    <!-- 0=Normal; 1=Consumidor final; -->
    <xsl:text>"consumer_indicator": </xsl:text>
    <xsl:choose>
     <xsl:when test="nfe:indFinal"><xsl:value-of select="nfe:indFinal"/></xsl:when>
     <xsl:otherwise>null</xsl:otherwise>
    </xsl:choose>
    <xsl:text>,</xsl:text>
    <!-- 0=Emissão de NF-e com aplicativo do contribuinte; -->
    <!-- 1=Emissão de NF-e avulsa pelo Fisco; -->
    <!-- 2=Emissão de NF-e avulsa, pelo contribuinte com seu certificado digital, através do site do Fisco; -->
    <!-- 3=Emissão NF-e pelo contribuinte com aplicativo fornecido pelo Fisco. -->
    <xsl:text>"issuing_process":"</xsl:text><xsl:value-of select="nfe:procEmi"/><xsl:text>"</xsl:text>
    <xsl:text>}</xsl:text>
  </xsl:template>
</xsl:stylesheet>