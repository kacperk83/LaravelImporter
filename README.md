# PixelIndustries

Bijlage:		
JSON-bestand:	"challenge.json".	

Opdracht	
	
Schijf	een	proces	dat	de	inhoud	van	het	JSON-bestand	op	een	nette	manier	wegschrijft	naar	
een	database.	
	
Liefst	als	een	achtergrondtaak	in	Laravel.	Gebruik	maken	van	Docker	mag,	maar	alleen	als	
dat	het	leuker	maakt.	
	
Voorwaarden:	
	
• [Primair]	Maak	het	proces	zo,	dat	elk	moment	kan	worden	afgekapt	(bijv.	door	een	
SIGTERM,	stroomuitval,	etc),	en	precies	door	kan	gaan	waar	het	laatst	gebleven	was	
(zonder	data	te	dupliceren). 1 	
• Gebruik	een	degelijk,	maar	niet	overdreven	database-model.		
Code	voor	Eloquent-models	en	relaties	zijn	hierbij	niet	zo	belangrijk,	het	gaat	ons	
meer	om	de	gegevensstructuur.	
• Verwerk	alleen	records	waarvoor	geldt	dat	de	leeftijd	tussen	de	18	en	65	ligt	(of	
onbekend	is).	
	
	
Bonus	
	
Ter	extra	uitdaging	geven	we	nog	het	volgende	in	overweging:	
	
• Wat	nu	als	het	bronbestand	ineens	50	keer	zo	groot	wordt?	
• Is	het	proces	makkelijk	in	te	zetten	voor	een	XML-	of	CSV-bestand	met	vergelijkbare	
content?	
• Stel	dat	alleen	records	moeten	worden	verwerkt	waarvoor	geldt	dat	het	creditcard-
nummer	drie	opeenvolgende	zelfde	cijfers	bevat,	hoe	zou	je	dat	aanpakken?
