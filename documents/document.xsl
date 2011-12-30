<?xml version="1.0" encoding="UTF-8"?>

<!--<xsl:stylesheet version="1.0"
      xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
      xmlns:fo="http://www.w3.org/1999/XSL/Format">
  <xsl:output method="xml" indent="yes"/>-->
  
<xsl:stylesheet version="1.1" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:fo="http://www.w3.org/1999/XSL/Format" exclude-result-prefixes="fo">
  <xsl:output method="xml" version="1.0" omit-xml-declaration="no" indent="yes"/>
  <!--<xsl:preserve-space elements="resume resume_line"/>-->
  <xsl:param name="versionParam" select="'1.0'"/>
  
  <!-- Déclare un formatage nommé 'financial' avec séparateur ',' pour les centièmes et ' ' entre les groupes de chiffre -->
  <xsl:decimal-format name="financial" decimal-separator="," grouping-separator=" " />
  
  <xsl:template match="facture">
    <fo:root xmlns:fo="http://www.w3.org/1999/XSL/Format">
      <fo:layout-master-set>
        <!--Modèle de page nommé page-initiale-->	
	      <fo:simple-page-master master-name="page-initiale" page-height="29.7cm" page-width="21cm" margin-left="1cm" margin-right="1cm" margin-top="1.275cm" margin-bottom="1cm">
	        <fo:region-body margin-bottom="1cm"/><!--corps du doc-->
	        <fo:region-after extent="6pt"/><!--pied de page-->
	      </fo:simple-page-master>
 
        <!--Modèle de page nommé Standard-->		
	      <fo:simple-page-master master-name="Standard" page-height="29.7cm" page-width="21cm" margin="1cm" >
	        <fo:region-body margin-bottom="1cm"/><!--corps du doc-->
	        <fo:region-after extent="6pt"/><!--pied de page-->
	      </fo:simple-page-master>
       
	      <!--Modèle d'une séquence de page  nommé Standard-->		
	      <fo:page-sequence-master master-name="mod">
            <fo:single-page-master-reference master-reference="page-initiale"/><!--modéle de la première page-->
	        <fo:repeatable-page-master-reference master-reference="Standard"/><!--modéle des autres pages-->
	      </fo:page-sequence-master>
	      
	    </fo:layout-master-set>
      
      <fo:page-sequence master-reference="mod" font-family="AndaleMono" font-style="normal" font-size="8pt">
      
        <fo:static-content flow-name="xsl-region-after">
          <fo:block font-size="6pt" text-align="center">
            SARL au capital de 9000 € <fo:external-graphic src="file:documents/images/puce_noire.svg" vertical-align="middle"/> 31, rue Louis Blanc 75010 Paris <fo:external-graphic src="file:documents/images/puce_noire.svg" vertical-align="middle"/> N° Siret  503 179 152 00023 <fo:external-graphic src="file:documents/images/puce_noire.svg" vertical-align="middle"/> N° TVA  FR 91503179152
          </fo:block>
          <!--<fo:block font-size="8pt" text-align="center">Page <fo:page-number/></fo:block>--><!--<fo:page-number/>permet de numéroter les pages-->
          
        </fo:static-content>

        <fo:flow flow-name="xsl-region-body" width="100%">

          <!-- Entete -->
          <fo:block line-height="1"
                    background-image="file:documents/images/logo_noir_bande.svg"
                    background-repeat="no-repeat">
            
            <fo:table table-layout="fixed" width="100%" border-collapse="separate">
              <fo:table-column column-width="77.4mm"/>
              <fo:table-column column-width="44.3mm"/>
              <fo:table-column column-width="68.3mm"/>
              <fo:table-body>
                
                <xsl:apply-templates select="client" />
                
                <fo:table-row height="1cm">
                  <fo:table-cell><fo:block></fo:block></fo:table-cell>
                  <fo:table-cell><fo:block></fo:block></fo:table-cell>
                  <fo:table-cell><fo:block></fo:block></fo:table-cell>
                </fo:table-row>

              </fo:table-body>
            </fo:table>  
          </fo:block>
          
          <!-- Description -->       
          <xsl:variable name="resume">
            <xsl:value-of select="resume"/>
          </xsl:variable>
          
          <xsl:choose>
            <xsl:when test="resume != ''">
            
              <fo:block margin-top="1cm">
                <xsl:apply-templates select="resume" />
              </fo:block>
  
              <!-- Sections -->
              <fo:block margin-top="2cm">
                <xsl:apply-templates select="section" />
              </fo:block>
            </xsl:when>
            <xsl:otherwise>
              <!-- Sections -->
              <fo:block margin-top="5cm">
                <xsl:apply-templates select="section" />
              </fo:block>
            </xsl:otherwise>
          </xsl:choose>
          
        
          <!-- Remise -->
          <xsl:if test="remise &gt; 0">
            <fo:block margin-top="7mm">
              <fo:table table-layout="fixed" width="100%" border-collapse="separate"  text-align="right" border-top="0.7pt solid black" border-color="rgb(0,0,0)">
                <fo:table-column column-width="109mm"/>
                <fo:table-column column-width="17mm"/>
                <fo:table-column column-width="32mm"/>
                <fo:table-column column-width="32mm"/>
              
                <fo:table-body>
                  <fo:table-row>
                    <fo:table-cell text-align="left" border-bottom="0.7pt solid black"  padding-top="2pt" padding-bottom="1pt">
                      <fo:block>
                        REMISE
                      </fo:block>
                    </fo:table-cell>
                    <fo:table-cell border-bottom="0.7pt solid black"><fo:block></fo:block></fo:table-cell>
                    <fo:table-cell text-align="right" border-bottom="0.7pt solid black" padding-top="2pt" padding-bottom="1pt">
                      <fo:block>
                        <xsl:value-of select="remise * 100"/> %
                      </fo:block>
                    </fo:table-cell>
                    
                    <fo:table-cell text-align="right" border-bottom="0.7pt solid black" padding-top="2pt" padding-bottom="1pt">
                      <fo:block>

                        <xsl:variable name="total">
                          <xsl:call-template name="addTotal">
                            <xsl:with-param name="node" select="section[1]"/>
                          </xsl:call-template>
                        </xsl:variable>

                        <xsl:value-of select="format-number($total * remise, '# ##0,00', 'financial')"/> €
                        
                      </fo:block>
                    </fo:table-cell>
                  </fo:table-row>
                </fo:table-body>
              </fo:table>
            </fo:block>
          </xsl:if>
          
          <!-- Totaux -->
          <fo:block margin-top="7mm" keep-together="always" keep-with-previous="always">
            <fo:table table-layout="fixed" width="100%" border-collapse="separate"  text-align="right">
            <fo:table-column column-width="140mm"/>
            <fo:table-column column-width="25mm"/>
            <fo:table-column column-width="25mm"/>
            
              <fo:table-body>
                <fo:table-row>
                  <fo:table-cell><fo:block></fo:block></fo:table-cell>
                  <fo:table-cell text-align="right">
                    <fo:block>
                      TOTAL HT
                    </fo:block>
                  </fo:table-cell>
                  
                  <fo:table-cell text-align="right">
                    <fo:block>
                      <xsl:variable name="total">
                        <xsl:call-template name="addTotal">
                          <xsl:with-param name="node" select="section[1]"/>
                        </xsl:call-template>
                      </xsl:variable>
                      
                      <xsl:value-of select="format-number($total * (1 - remise), '# ##0,00', 'financial')"/> €
                    </fo:block>
                  </fo:table-cell>
                </fo:table-row>

                <fo:table-row>
                  <fo:table-cell><fo:block></fo:block></fo:table-cell>
                  <fo:table-cell text-align="right">
                    <fo:block>
                      TVA (<xsl:value-of select="tva * 100"/> %)
                    </fo:block>
                  </fo:table-cell>
                  
                  <fo:table-cell text-align="right">
                    <fo:block>
                      <xsl:variable name="total">
                        <xsl:call-template name="addTotal">
                          <xsl:with-param name="node" select="section[1]"/>
                        </xsl:call-template>
                      </xsl:variable>
                      
                      <xsl:value-of select="format-number(($total * (1 - remise )) * tva, '# ##0,00', 'financial')"/> €
                    </fo:block>
                  </fo:table-cell>
                </fo:table-row>

                
                <fo:table-row font-weight="bold" >
                  <fo:table-cell><fo:block></fo:block></fo:table-cell>
                  <fo:table-cell text-align="right" padding-top="5pt">
                    <fo:block>
                      TOTAL TTC
                    </fo:block>
                  </fo:table-cell>
                  
                  <fo:table-cell text-align="right" padding-top="5pt">
                    <fo:block>
                      <xsl:variable name="total">
                        <xsl:call-template name="addTotal">
                          <xsl:with-param name="node" select="section[1]"/>
                        </xsl:call-template>
                      </xsl:variable>
                      
                      <xsl:value-of select="format-number(($total * (1 - remise )) * (1 + tva), '# ##0,00', 'financial')"/> €
                    </fo:block>
                  </fo:table-cell>
                </fo:table-row>
                
                <!-- Acompte -->
                <xsl:variable name="pourc_acompte">
            	  <xsl:value-of select="pourc_acompte"/>
          		</xsl:variable>
          	    <xsl:choose>
            	  <xsl:when test="pourc_acompte > 0">
            	  
	                <fo:table-row font-weight="bold" >
	                  <fo:table-cell><fo:block></fo:block></fo:table-cell>
	                  <fo:table-cell text-align="right" padding-top="5pt">
	                    <fo:block  >
	                      ACOMPTE
	                    </fo:block>
	                  </fo:table-cell>
	                  
	                  <fo:table-cell text-align="right" padding-top="5pt">
	                    <fo:block>
	                      <xsl:value-of select="format-number(pourc_acompte * 100, '#0.00')"/> %
	                    </fo:block>
	                  </fo:table-cell>
	                </fo:table-row>
	                
	                <fo:table-row font-weight="bold" >
	                  <fo:table-cell><fo:block></fo:block></fo:table-cell>
	                  <fo:table-cell text-align="right" padding-top="5pt">
	                    <fo:block  >
	                      NET À PAYER
	                    </fo:block>
	                  </fo:table-cell>
	                  
	                  <fo:table-cell text-align="right" padding-top="5pt">
	                    <fo:block>
	                      <xsl:variable name="total">
	                        <xsl:call-template name="addTotal">
	                          <xsl:with-param name="node" select="section[1]"/>
	                        </xsl:call-template>
	                      </xsl:variable>
	                      
	                      <xsl:value-of select="format-number((($total * (1 - remise )) * (1 + tva)) * pourc_acompte, '# ##0,00', 'financial')"/> €
	                    </fo:block>
	                  </fo:table-cell>
	                </fo:table-row>
                  </xsl:when>
                  <xsl:otherwise>
                  </xsl:otherwise>
                </xsl:choose>
                
                <!-- Acompte versé-->
                <xsl:variable name="acompte_verse">
            	  <xsl:value-of select="acompte_verse"/>
          		</xsl:variable>
          	    <xsl:choose>
            	  <xsl:when test="acompte_verse > 0">
            	  
	                <fo:table-row font-weight="bold" >
	                  <fo:table-cell><fo:block></fo:block></fo:table-cell>
	                  <fo:table-cell text-align="right" padding-top="5pt">
	                    <fo:block  >
	                      ACOMPTE VERSÉ
	                    </fo:block>
	                  </fo:table-cell>
	                  
	                  <fo:table-cell text-align="right" padding-top="5pt">
	                    <fo:block>
	                      <xsl:value-of select="format-number($acompte_verse, '# ##0,00', 'financial')"/> €
	                    </fo:block>
	                  </fo:table-cell>
	                </fo:table-row>
	                
	                <fo:table-row font-weight="bold" >
	                  <fo:table-cell><fo:block></fo:block></fo:table-cell>
	                  <fo:table-cell text-align="right" padding-top="5pt">
	                    <fo:block  >
	                      NET À PAYER
	                    </fo:block>
	                  </fo:table-cell>
	                  
	                  <fo:table-cell text-align="right" padding-top="5pt">
	                    <fo:block>
	                      <xsl:variable name="total">
	                        <xsl:call-template name="addTotal">
	                          <xsl:with-param name="node" select="section[1]"/>
	                        </xsl:call-template>
	                      </xsl:variable>
	                      
	                      <xsl:value-of select="format-number(($total * (1 - remise )) * (1 + tva) - acompte_verse, '# ##0,00', 'financial')"/> €
	                    </fo:block>
	                  </fo:table-cell>
	                </fo:table-row>
                  </xsl:when>
                  <xsl:otherwise>
                  </xsl:otherwise>
                </xsl:choose>
                
              </fo:table-body>
            </fo:table>
          </fo:block>
          
          <!-- Conditions -->
          <fo:block margin-top="1cm" margin-right="70mm">
            <xsl:apply-templates select="conditions" />
          </fo:block>
          
          <!-- Signature -->
          
          <xsl:variable name="type">
            <xsl:value-of select="/facture/type"/>
          </xsl:variable>
          
          <xsl:choose>
            <xsl:when test="$type='devis'">
              <fo:block margin-top="1cm" text-align="right">
              date et signature du client
              </fo:block>
              <fo:block text-align="right">
              (précédé de la mention «bon pour accord»)
              </fo:block>
            </xsl:when>
            <xsl:otherwise>
            </xsl:otherwise>
          </xsl:choose>
       
        </fo:flow>
        
      </fo:page-sequence>
      
    </fo:root>
  </xsl:template>
 
 
  <xsl:template match="client">
  
    <fo:table-row>
      <fo:table-cell><fo:block></fo:block></fo:table-cell>
      <fo:table-cell><fo:block></fo:block></fo:table-cell>
      <fo:table-cell>
      <fo:block font-size="12pt" font-weight="bold" line-height="2">
        <xsl:variable name="type">
            <xsl:value-of select="/facture/type"/>
        </xsl:variable>
        <xsl:variable name="acompte">
            <xsl:value-of select="/facture/acompte"/>
        </xsl:variable>
          
        <xsl:choose>
          <xsl:when test="$type='facture'">
            <xsl:choose>
              <xsl:when test="$acompte='true'">
                <fo:block font-size="12pt" font-weight="bold" line-height="2">
                  FACTURE ACOMPTE N° <xsl:value-of select="/facture/number"/>
                </fo:block>
              </xsl:when>
              <xsl:otherwise>
                <fo:block font-size="12pt" font-weight="bold" line-height="2">
                  FACTURE N° <xsl:value-of select="/facture/number"/>
                </fo:block>
              </xsl:otherwise>
            </xsl:choose>  
          </xsl:when>
          <xsl:when test="$type='devis'">
            <fo:block font-size="12pt" font-weight="bold" line-height="2">
              DEVIS N° <xsl:value-of select="/facture/number"/>
            </fo:block>
          </xsl:when>
          <xsl:when test="$type='estimation'">
            <fo:block font-size="12pt" font-weight="bold" line-height="2">
              ESTIMATION
            </fo:block>
          </xsl:when>
          <xsl:otherwise>
          </xsl:otherwise>
        </xsl:choose>
        </fo:block>
      </fo:table-cell>
    </fo:table-row>

    <fo:table-row>
      <fo:table-cell><fo:block></fo:block></fo:table-cell>
      <fo:table-cell><fo:block></fo:block></fo:table-cell>
      <fo:table-cell>
        <fo:block line-height="2">
          <xsl:value-of select="/facture/date"/>
        </fo:block>
      </fo:table-cell>
    </fo:table-row>

    <fo:table-row>
      <fo:table-cell><fo:block></fo:block></fo:table-cell>
      <fo:table-cell><fo:block></fo:block></fo:table-cell>
      <fo:table-cell>
        <fo:block line-height="2">
          Affaire suivie par <xsl:value-of select="/facture/follower"/>
        </fo:block>
      </fo:table-cell>
    </fo:table-row>

    <fo:table-row>
      <fo:table-cell><fo:block></fo:block></fo:table-cell>
      <fo:table-cell>
        <fo:block>
          31, rue Louis Blanc
        </fo:block>
      </fo:table-cell>
      <fo:table-cell>
        <fo:block>
          <xsl:value-of select="name"/>
        </fo:block>
      </fo:table-cell>
    </fo:table-row>
 
    <fo:table-row>
      <fo:table-cell><fo:block></fo:block></fo:table-cell>
      <fo:table-cell>
        <fo:block>
          75010 Paris
        </fo:block>
      </fo:table-cell>
      <fo:table-cell>
        <fo:block>
          <xsl:value-of select="address"/>
        </fo:block>
      </fo:table-cell>
    </fo:table-row>
    
    <fo:table-row>
      <fo:table-cell><fo:block></fo:block></fo:table-cell>
      <fo:table-cell>
        <fo:block>
          Tel. : 01 80 50 76 14
        </fo:block>
      </fo:table-cell>
      <fo:table-cell>
        <fo:block>
          <xsl:value-of select="zip"/> <xsl:text disable-output-escaping="yes">&#32;</xsl:text> <xsl:value-of select="city"/>
        </fo:block>
      </fo:table-cell>
    </fo:table-row>
    
    <fo:table-row>
      <fo:table-cell><fo:block></fo:block></fo:table-cell>
      <fo:table-cell>
        <fo:block>
          www.soixantecircuits.fr
        </fo:block>
      </fo:table-cell>
      <fo:table-cell>
        <fo:block>
          <xsl:value-of select="country"/>
        </fo:block>
      </fo:table-cell>
    </fo:table-row>
  </xsl:template> 
  
  <xsl:template match="resume">
    <fo:block font-size="12pt" font-weight="bold" margin-top="2cm">
      DESCRIPTION
    </fo:block>
    <xsl:apply-templates select="resume_line"/>
  </xsl:template>
  
  <xsl:template match="resume_line">
    <fo:block white-space-collapse="false" white-space-treatment="preserve">
      <!--<xsl:value-of select="."/>-->
      <xsl:value-of select="translate(., '&#160;', ' ')"/> <!-- transforme les espace insecables en espaces normaux -->
    </fo:block>
  </xsl:template>
  
  <xsl:template match="section">
  
  <fo:block keep-together.within-column="always"> 
  
    <fo:block font-size="12pt" font-weight="bold" margin-top="0.5cm">
      <xsl:apply-templates select="title"/>
    </fo:block>

    <fo:block space-before="1mm">
      <fo:table table-layout="fixed" width="100%" border-collapse="separate" text-align="right" border-top="0.7pt solid black" border-color="rgb(0,0,0)">
        <fo:table-column column-width="109mm"/>
        <fo:table-column column-width="17mm"/>
        <fo:table-column column-width="32mm"/>
        <fo:table-column column-width="32mm"/>
        
        <fo:table-header>
          <fo:table-row>
            <fo:table-cell text-align="left" padding-top="2pt" padding-bottom="1pt">
                <fo:block font-weight="bold">DÉSIGNATION</fo:block>
            </fo:table-cell>
            <fo:table-cell padding-top="2pt" padding-bottom="1pt">
                <fo:block font-weight="bold">QTÉ</fo:block>
            </fo:table-cell>
            <fo:table-cell padding-top="2pt" padding-bottom="1pt">
                <fo:block font-weight="bold">PRIX UNIT. HT</fo:block>
            </fo:table-cell>
            <fo:table-cell padding-top="2pt" padding-bottom="1pt">
                <fo:block font-weight="bold">MONTANT HT</fo:block>
            </fo:table-cell>
          </fo:table-row>
        </fo:table-header>
        
        <fo:table-footer>
          <fo:table-row>
              <fo:table-cell text-align="left" border-top="0.7pt solid black" border-bottom="0.7pt solid black" padding-top="2pt" padding-bottom="1pt">
                <fo:block>
                  TOTAL <xsl:apply-templates select="title"/>
                </fo:block>
              </fo:table-cell>
              <fo:table-cell border-top="0.7pt solid black" border-bottom="0.7pt solid black" padding-top="2pt" padding-bottom="1pt"> 
                <fo:block></fo:block>
              </fo:table-cell>
              <fo:table-cell border-top="0.7pt solid black" border-bottom="0.7pt solid black" padding-top="2pt" padding-bottom="1pt">
                  <fo:block></fo:block>
              </fo:table-cell>
              <fo:table-cell border-top="0.7pt solid black" border-bottom="0.7pt solid black" padding-top="2pt" padding-bottom="1pt">
                <fo:block>
                  <xsl:variable name="total">
                    <xsl:call-template name="addSubTotal">
                      <xsl:with-param name="node" select="item[1]"/>
                    </xsl:call-template>
                  </xsl:variable>
                  <xsl:value-of select="format-number($total, '# ##0,00', 'financial')"/> €
                </fo:block>
              </fo:table-cell>
          </fo:table-row>
        </fo:table-footer>

        <fo:table-body>
          <xsl:apply-templates select="item"/>
        </fo:table-body>
  
      </fo:table>  
    </fo:block>
  </fo:block>  
  </xsl:template>
  
  <xsl:template match="item">

    <fo:table-row>
      <fo:table-cell text-align="left" border-top="0.7pt solid black" padding-top="2pt" padding-bottom="1pt">
        <fo:block>
          <!--<xsl:value-of select="description"/>-->
          <xsl:value-of select="translate(description, '&#160;', ' ')"/>
        </fo:block>
      </fo:table-cell>
      <fo:table-cell border-top="0.7pt solid black" padding-top="2pt" padding-bottom="1pt">
        <fo:block>
          <xsl:value-of select="quantity"/>
        </fo:block>
      </fo:table-cell>
      <fo:table-cell border-top="0.7pt solid black" padding-top="2pt" padding-bottom="1pt">
        <fo:block>
          <xsl:value-of select="format-number(unit_price, '# ##0,00', 'financial')"/> €
        </fo:block>
      </fo:table-cell>
      <fo:table-cell border-top="0.7pt solid black" padding-top="2pt" padding-bottom="1pt">
        <fo:block>
          <xsl:value-of select="format-number(quantity * unit_price, '# ##0,00', 'financial')"/> €
        </fo:block>
      </fo:table-cell>
    </fo:table-row>
  </xsl:template>
  
  <xsl:template match="conditions">
    <xsl:apply-templates select="conditions_line"/>
  </xsl:template>
  
  <xsl:template match="conditions_line">
    <fo:block white-space-collapse="false" white-space-treatment="preserve">
      <xsl:value-of select="translate(., '&#160;', ' ')"/>
    </fo:block>
  </xsl:template>
  
  <!-- Recursive template. It will calculate total amount 
       from the all following items -->
  <xsl:template name="addSubTotal">
    <xsl:param name="node" select="."/>
    <xsl:variable name="sum-of-rest">
      <xsl:choose>
        <xsl:when test="$node/following-sibling::item">
          <xsl:call-template name="addSubTotal">
            <xsl:with-param name="node"
              select="$node/following-sibling::item[1]"/>
          </xsl:call-template>
        </xsl:when>
        <xsl:otherwise>0</xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <xsl:value-of select="($node/quantity)*($node/unit_price) + $sum-of-rest"/>  
  </xsl:template>
  
  <!-- Recursive template. It will calculate total amount 
       from the all following items in all following sections -->
  <xsl:template name="addTotal">
    <xsl:param name="node" select="."/>
    <xsl:variable name="sum-of-rest">
      <xsl:choose>
        <xsl:when test="$node/following-sibling::section">
          <xsl:call-template name="addTotal">
            <xsl:with-param name="node"
                select="$node/following-sibling::section[1]"/>
          </xsl:call-template>
        </xsl:when>
        <xsl:otherwise>0</xsl:otherwise>
      </xsl:choose>
    </xsl:variable>

    <xsl:variable name="sum">
      <xsl:call-template name="addSubTotal">
        <xsl:with-param name="node" select="$node/item[1]"/>
      </xsl:call-template>
    </xsl:variable>

    <xsl:value-of select="$sum + $sum-of-rest"/> 
  </xsl:template>
  
  
  
</xsl:stylesheet>
