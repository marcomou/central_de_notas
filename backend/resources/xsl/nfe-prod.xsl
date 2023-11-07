<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:nfe="http://www.portalfiscal.inf.br/nfe"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

  <xsl:template mode="prodElem" match="nfe:prod">
    <!-- Preencher com CFOP, caso se trate de itens não relacionados com mercadorias/produtos  -->
    <!-- e que o contribuinte não possua codificação própria. Formato: ”CFOP9999” -->
    <xsl:text>"product_code":"</xsl:text><xsl:value-of select="nfe:cProd"/><xsl:text>",</xsl:text>
    <!-- Preencher com o código GTIN-8, GTIN-12, GTIN-13 ou GTIN-14  -->
    <!-- (antigos códigos EAN, UPC e DUN-14), não informar o conteúdo da TAG  -->
    <!-- em caso de o produto não possuir este código. -->
    <xsl:text>"ean_code":"</xsl:text><xsl:value-of select="nfe:cEAN"/><xsl:text>",</xsl:text>
    <!--  -->
    <xsl:text>"product_description":"</xsl:text><xsl:value-of select="nfe:xProd"/><xsl:text>",</xsl:text>
    <!-- Obrigatória informação do NCM completo (8 dígitos). -->
    <!-- Nota: Em caso de item de serviço ou item que não tenham produto  -->
    <!-- (ex. transferência de crédito, crédito do ativo imobilizado, etc.),  -->
    <!-- informar o valor 00 (dois zeros). (NT 2014/004) -->
    <xsl:text>"ncm":"</xsl:text><xsl:value-of select="nfe:NCM"/><xsl:text>",</xsl:text>
    <!-- Código Fiscal de Operações e Prestações -->
    <!-- Utilizar Tabela de CFOP. -->
    <xsl:text>"cfop":"</xsl:text><xsl:value-of select="nfe:CFOP"/><xsl:text>",</xsl:text>
    <!-- Unidade Comercial -->
    <xsl:text>"comercial_unit":"</xsl:text><xsl:value-of select="nfe:uCom"/><xsl:text>",</xsl:text>
    <!-- Quantidade Comercial -->
    <xsl:text>"comercial_quantity":"</xsl:text><xsl:value-of select="nfe:qCom"/><xsl:text>",</xsl:text>
    <!-- Informar o valor unitário de comercialização do produto, campo meramente informativo,  -->
    <!-- o contribuinte pode utilizar a precisão desejada (0-10 decimais).  -->
    <!-- Para efeitos de cálculo, o valor unitário será obtido -->
     <!-- pela divisão do valor do produto pela quantidade comercial. (v2.0) -->
    <xsl:text>"comercial_unit_value":"</xsl:text><xsl:value-of select="nfe:vUnCom"/><xsl:text>",</xsl:text>
    <!-- Valor Total Bruto dos Produtos ou Serviços -->
    <xsl:text>"total_gross_value":"</xsl:text><xsl:value-of select="nfe:vProd"/><xsl:text>",</xsl:text>
    <!-- Preencher com o código GTIN-8, GTIN-12, GTIN-13 ou GTIN-14  -->
    <!-- (antigos códigos EAN, UPC e DUN-14) da unidade tributável do produto,  -->
    <!-- não informar o conteúdo da TAG em caso de o produto não possuir este código. -->
    <xsl:text>"taxable_ean_code":"</xsl:text><xsl:value-of select="nfe:cEANTrib"/><xsl:text>",</xsl:text>
    <!-- Unidade Tributável -->
    <xsl:text>"taxable_unit":"</xsl:text><xsl:value-of select="nfe:uTrib"/><xsl:text>",</xsl:text>
    <!-- Informar a quantidade de tributação do produto (v2.0). -->
    <xsl:text>"taxable_quantity":"</xsl:text><xsl:value-of select="nfe:qTrib"/><xsl:text>",</xsl:text>
    <!-- Informar o valor unitário de tributação do produto, campo meramente informativo,  -->
    <!-- o contribuinte pode utilizar a precisão desejada (0-10 decimais).  -->
    <!-- Para efeitos de cálculo, o valor unitário será obtido  -->
    <!-- pela divisão do valor do produto pela quantidade tributável (NT 2013/003). -->
    <xsl:text>"taxable_unit_value":"</xsl:text><xsl:value-of select="nfe:vUnTrib"/><xsl:text>",</xsl:text>
    <!-- 0=Valor do item (vProd) não compõe o valor total da NF-e -->
    <!-- 1=Valor do item (vProd) compõe o valor total da NF-e (vProd) (v2.0) -->

    <xsl:text>"invoice_value_compound":"</xsl:text><xsl:value-of select="nfe:indTot"/><xsl:text>"</xsl:text>
  </xsl:template>

  <xsl:template mode="impostoElem" match="nfe:imposto">
    <xsl:text>"taxes":</xsl:text>
    <xsl:text>{</xsl:text>
      <xsl:text>"icms_icms40_orig":"</xsl:text><xsl:value-of select="nfe:ICMS/nfe:ICMS40/nfe:orig"/><xsl:text>",</xsl:text>
      <xsl:text>"icms_icms40_cst":"</xsl:text><xsl:value-of select="nfe:ICMS/nfe:ICMS40/nfe:CST"/><xsl:text>",</xsl:text>
      <xsl:text>"ipi_cenq":"</xsl:text><xsl:value-of select="nfe:IPI/nfe:cEnq"/><xsl:text>",</xsl:text>
      <xsl:text>"ipi_ipint_cst":"</xsl:text><xsl:value-of select="nfe:IPI/nfe:IPINT/nfe:CST"/><xsl:text>",</xsl:text>
      <xsl:text>"pis_pisaliq_cst":"</xsl:text><xsl:value-of select="nfe:PIS/nfe:PISAliq/nfe:CST"/><xsl:text>",</xsl:text>
      <xsl:text>"pis_pisaliq_vbc":"</xsl:text><xsl:value-of select="nfe:PIS/nfe:PISAliq/nfe:vBC"/><xsl:text>",</xsl:text>
      <xsl:text>"pis_pisaliq_ppis":"</xsl:text><xsl:value-of select="nfe:PIS/nfe:PISAliq/nfe:pPIS"/><xsl:text>",</xsl:text>
      <xsl:text>"pis_pisaliq_vpis":"</xsl:text><xsl:value-of select="nfe:PIS/nfe:PISAliq/nfe:vPIS"/><xsl:text>",</xsl:text>
      <xsl:text>"cofins_cofinsaliq_cst":"</xsl:text><xsl:value-of select="nfe:COFINS/nfe:COFINSAliq/nfe:CST"/><xsl:text>",</xsl:text>
      <xsl:text>"cofins_cofinsaliq_vbc":"</xsl:text><xsl:value-of select="nfe:COFINS/nfe:COFINSAliq/nfe:vBC"/><xsl:text>",</xsl:text>
      <xsl:text>"cofins_cofinsaliq_pcofins":"</xsl:text><xsl:value-of select="nfe:COFINS/nfe:COFINSAliq/nfe:pCOFINS"/><xsl:text>",</xsl:text>
      <xsl:text>"cofins_cofinsaliq_vcofins":"</xsl:text><xsl:value-of select="nfe:COFINS/nfe:COFINSAliq/nfe:vCOFINS"/><xsl:text>"</xsl:text>
    <xsl:text>}</xsl:text>
  </xsl:template>
</xsl:stylesheet>