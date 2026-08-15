<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte uMap Responsive</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
        .map-container {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
        }

        .map-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }
    </style>
</head>
<body>
<div class="map-container">
    <iframe src="
https://umap.openstreetmap.fr/fr/map/points-de-prise-en-charge-jo-2024-energy-kids-acad_1082518#14/48.8689/2.3138?scaleControl=false&miniMap=true&scrollWheelZoom=true&zoomControl=true&allowFullScreen=true&moreControl=true&searchControl=true&tilelayersControl=true&embedOptions=miniMap,scaleControl">
    </iframe>
</div>
</body>
</html>
