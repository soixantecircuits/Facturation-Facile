#Facturation Facile

## Résumé

Facturation facile est une webapplication légère qui permet d'éditer des factures en ligne.
Elle permet de les exporter en pdf.

## Requis

* Serveur >= php5.5
* Serveur >= mysql 4
* apache fop
  You can install it by runngin :
  brew install fop (OSX)
  apt-get install fop (Ubuntu/debian)
  or go here :
  http://xmlgraphics.apache.org/fop/download.html
* java JDK

## Améliorations

* écriture des factures pdf en javascript côté client :
    https://github.com/mozilla/pdf.js

  ou côté serveur :
    http://pdfkit.org/ (node.js)

* Sauvegarde des noms des clients

* Récupération des noms des clients avec fichiers d'adresses + contact : (adresse physique, mails, téléphone)

* Indication statu de la facturation

* Mailer pour indiquer la création d'une facture, d'un devis, ou d'une estimation

* Indication du créateur de la facture

* Amélioration des feuilles de style
