<?php
/**
 * Created by PhpStorm.
 * User: F
 * Date: 9/23/2016
 * Time: 3:51 AM
 */

$desa_id = "bokor";
$server_name = $_SERVER["SERVER_NAME"];
$server_splits = explode(".", $server_name);
if($server_splits[0].".desa.id" == $server_name || $server_splits[0].".sideka.id" == $server_name){
    $desa_id = $server_splits[0];
}

$ckan_host = "http://data.prakarsadesa.id";
#$ckan_host = "http://ckan.neon.microvac:5000";
$package_id = $desa_id."-pemetaan";
$json = @file_get_contents($ckan_host . '/api/3/action/package_show?id=' . $package_id);
$package_exists = json_decode($json)->success;
?>
<?php if($package_exists) { ?>
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,300" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="/wp-content/plugins/sideka/lib/leaflet/leaflet.css">

	<script src="/wp-content/plugins/sideka/lib/jquery-3.1.1.min.js"></script>
	<!-- <script src="lib/bootstrap/bootstrap.min.js"></script> -->
	<script src="/wp-content/plugins/sideka/lib/leaflet/leaflet.js"></script>
	<script src="/wp-content/plugins/sideka/lib/angular.min.js"></script>
	<script src="/wp-content/plugins/sideka/lib/angular-animate.min.js"></script>
	<script src="/wp-content/plugins/sideka/lib/angular-touch.min.js"></script>
	<script src="/wp-content/plugins/sideka/lib/angular-simple-logger.min.js"></script>
	<script src="/wp-content/plugins/sideka/lib/ui-leaflet.min.js"></script>

    <style>
    </style>

    <div class="clearfix" ng-app="petaDesa">
        <h4 class="mh-widget-title">
            <span class="mh-widget-title-inner"><a href="#" class="mh-widget-title-link">Peta Desa</a></span>
        </h4>
        <div ng-controller="petaDesaController">
		<leaflet id="petaDesa" class="fullscreen" style="height: 500px; width: 100%;" tiles="petaDesaController.tiles" lf-center="petaDesaController.center" defaults="petaDesaController.defaults"></leaflet>
        </div>
    </div>


    <link href="/wp-content/plugins/sideka/nv.d3.css" rel="stylesheet">
    <script src="/wp-content/plugins/sideka/d3.v3.js"></script>
    <script src="/wp-content/plugins/sideka/nv.d3.js"></script>

    <script type="text/javascript">
        document.getElementsByClassName("entry-header")[0].remove();
        var package_id = "<?= $package_id ?>";
        var ckan_host = "<?= $ckan_host ?>";
        var package = <?= $json ?>;


	(function () {
	petaDesa = angular.module('petaDesa', [
		'nemLogging',		
		'ui-leaflet',
	]);


	var petaDesaController = petaDesa.controller('petaDesaController', ['$scope', '$http', '$q', '$interval', 'leafletData', 
		function ($scope, $http, $q, $interval, leafletData) {			
		var petaDesa = this;

		petaDesa.villages = [
			{ id: 'rawabiru', name: 'Rawa Biru - Merauke', lat: -8.6810, lng: 140.9022, zoom: 12 },
			{ id: 'alas', name: 'Alas - Belu', lat: -9.4020, lng: 125.0436, zoom: 15 },
			{ id: 'bokor', name: 'Bokor - Kepulauan Meranti', lat: 1.0604, lng: 102.7601, zoom: 14 },
		];
		petaDesa.selectedVillage = petaDesa.villages[0];				

		petaDesa.indicators = [
			{ id: 'anggaran', name: 'Anggaran' },
			{ id: 'batas-desa', name: 'Batas Desa' },
			{ id: 'tutupan-lahan', name: 'Tutupan Lahan' },
			{ id: 'jalan', name: 'Jalan' },
			{ id: 'bangunan', name: 'Bangunan' },
		];

		petaDesa.geoJson = {};
		petaDesa.geoJsonLayers = [];
		petaDesa.center = {};
		petaDesa.defaults = {
			zoomAnimation: true,
			zoomControl: false,
		};
		petaDesa.tiles = {
			url: 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
			options: {
				attribution: ''
			}
		};	

		petaDesa.getGeoJson = function (names, replace) {
			var promises = [];
			names.forEach(function (name) {
				var p = package.result.resources.filter(function(r) {return r.name === name})[0];
				if(!p){
					return;
				}
				console.log(p.url);
				uri = p.url;
				promises.push(
					$http({
						method: 'GET',
						url: uri
					})
				);
			});

			$q.all(promises).then(function (results, status) {
				if (replace)
					petaDesa.geoJson = {};

				results.forEach(function (result) {
					if (Object.keys(petaDesa.geoJson).length === 0)
						petaDesa.geoJson = result.data;
					else
						petaDesa.geoJson.features = petaDesa.geoJson.features.concat(result.data.features);
				});
				petaDesa.loadGeoJson(replace);
			});
		};

		petaDesa.loadGeoJson = function (replace) {
			leafletData.getMap('petaDesa').then(function (map) {
				if (replace) {
					petaDesa.geoJsonLayers.forEach(function (layers) {
						map.removeLayer(layers);
					});
					petaDesa.geoJsonLayers.length = 0;
				};
				
				var geoJsonLayer = L.geoJSON(petaDesa.geoJson, {
					weight: 0,
					fillOpacity: 1,
					style: function (feature) {
						if (feature.properties['Nama_Jalan'] !== undefined) {
							return { color: 'red' };
						}
						else if (feature.properties['Keterangan'] !== undefined) {
							console.log(feature.properties['Keterangan']);
							//theme: https://snazzymaps.com/style/21/hopper
							switch (feature.properties['Keterangan']) {
								case 'Bangunan': 
								case 'PLTD': 
								case 'Pos TNI': 
								case 'TPU': 
								case 'Sarana Olahraga':
								case 'Taman Wisata Budaya':
								case 'Pelabuhan':
								case 'Lapangan Sepak Bola':
								case 'Lapangan Volley':
								case 'Bak Penampungan PDAM': 
									return { color: 'rgb(189,56,26)', weight: 1 };

								case 'Area Permukiman': 
								case 'Area Gedung': 
								case 'Area Industri Tidak Terpakai': 
									return { color: 'rgb(171, 180, 164)' };


								//case 'Rawa': return { color: 'rgb(193, 249, 255)' };
								case 'Embung': 
								case 'Sungai':
								case 'Sungai Kicak': 
									return { color: 'rgb(0,116,130)' };

								case 'Rawa': 
									return { color: '#9EDEE6' };

								case 'Jalan': 
								case 'Koridor Jalan': 
									return { color: 'rgb(217, 221, 217)', weight: 1 };

								case 'Hutan': 
								case 'Hutan Desa': 
								case 'Hutan Manggrove': 
								case 'Semak Belukar': 
									return { color: '#48712A' };
									
								case 'Rumput Rawa': 
								case 'Lahan Tidur': 
								case 'Tanah Terbuka': 
								case 'Kebun Mahoni': 
								case 'Kebun Campuran':
								case 'Kebun Kemiri': 
								case 'Kebun Kumbili': 
								case 'Kebun Kelapa': 
								case 'Hutan Desa': 
								case 'Kebun Sagu': 
								case 'Kebun Karet': 
								case 'Kebun Durian': 
								case 'Kebun Manggis': 
									return { color: 'rgb(190,207,178)' };

								default: 
									return { color: 'rgb(190,207,178)' };
							}
						}
						else {
							return { color: '#333333', weight: 5 };
						}
					},

					onEachFeature: function(feature, layer) {	
						var popup = L.popup().setContent(feature.properties["Keterangan"]);
						layer.bindPopup(popup);											
						layer.on({
							'click': function(e) {																
							} 
						});
					}
				});

				petaDesa.geoJsonLayers.push(geoJsonLayer);
				geoJsonLayer.addTo(map);						
			});
		};

		var oldVillageId = null;
		petaDesa.changeIndicator = function () {
			petaDesa.getGeoJson([
				'Tutupan Lahan',
				'Batas',
				'Bangunan',
			], true);

		};
		leafletData.getMap('petaDesa').then(function (map) {
			map.on("zoomend", petaDesa.changeIndicator);
		});

		petaDesa.changeVillage = function () {
			leafletData.getMap('petaDesa').then(function(map){
				map.flyTo([petaDesa.selectedVillage.lat, petaDesa.selectedVillage.lng], petaDesa.selectedVillage.zoom);
			});
		};

		petaDesa.changeState = function () {	
			petaDesa.changeVillage();
		}


		petaDesa.changeState();

	}]);


})();


    </script>
<?php } else { ?>

<p>Data pemetaan belum diunggah</p>

<?php } ?>


