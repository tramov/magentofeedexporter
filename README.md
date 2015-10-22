# Magento Feed Exporter

Basic PHP commandline script to export all the products in a Magento store 
to a Twenga XML formatted file. 

Usage:

    php twenga.php --magedir=.. --shipping=6.60 --feedfile=output.xml --store=default
  
    --magedir   Req. the path where Magento is installed
    --shipping  Req. constant used to indicate shipping fee, for more complex
                logic, you need to build your own
    --feedfile  Opt. Name of the exported XML file (output.xml)
    --store     Opt. Id of the Magento store to export (default)

Caveats:

* No variable shipping prices
* All products are flagged as available (they might not be) 
* All products are flagged as new

##See also

*Twenga*

https://rts.twenga.fr/assets/media/doc-feed/Twenga_Feed_requirements_B2B_en_V14_1.pdf
