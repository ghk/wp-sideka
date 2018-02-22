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
$package_exists = true;//json_decode($json)->success;
$current_url = '/wp-content/plugins/sideka/page/';
?>
<?php if($package_exists) { ?>
	<script src="http://d3js.org/d3.v3.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@turf/turf@5/turf.min.js"></script>
	<style>
		#count-summary {
			width: 100%;
			text-align: center;
			margin: -15px 0 0 0;
		}
		#count-summary dt {
			text-transform: uppercase;
			margin-top: 15px;
			list-style-type: none;
			margin-left: 0;
			font-weight: normal;
			font-size: 16px;
		}
		#count-summary dd {
			list-style-type: none;
			font-size: 22px;
			margin-left: 0;
		}
	</style>

			<div class="mh-wrapper clearfix"><article id="page-8" class="post-8 page type-page status-publish hentry">
			<article id="page-8" class="post-8 page type-page status-publish hentry">
				<div class="entry-content clearfix">
					<div class="clearfix">
						<div class="clearfix" style="margin-bottom: 20px;">
							<h4 class="mh-widget-title">
								<span class="mh-widget-title-inner"><a class="mh-widget-title-link">Peta Desa</a></span>
							</h4>
							<div class="mh-widget-col-1 mh-sidebar">
								<dl id="count-summary" class="larger">
									<dt class="required">Luas Desa</dt>
									<dd id="boundary-area"></dd>
								</dl>
							</div>
							<div class="mh-widget-col-1 mh-sidebar">
								<dl id="count-summary" class="larger">
								<dt class="required">Keliling Desa</dt>
								<dd id="boundary-circumference">1280 km</dd>
								</dl>
							</div>
							<div class="mh-widget-col-1 mh-sidebar">
								<dl id="count-summary" class="larger">
								<dt class="required">Total Panjang Jalan</dt>
								<dd id="road-length">2000 km</dd>
								</dl>
							</div>
							<div class="svg-container" style="margin-left: 150px;"></div>
						</div>
						<div class="clearfix" style="margin-bottom: 20px;">
							<h4 class="mh-widget-title">
								<span class="mh-widget-title-inner"><a class="mh-widget-title-link">Lahan</a></span>
							</h4>
							<div class="mh-widget-col-1 mh-sidebar">
								<dl id="count-summary" class="larger">
									<dt class="required"><img src="<?php echo $current_url; ?>/images/hutan.png" style="width: 30px; height: 30px;"/> Hutan</dt>
									<dd id="count-forest"></dd>
								</dl>
							</div>
							<div class="mh-widget-col-1 mh-sidebar">
								<dl id="count-summary" class="larger">
								<dt class="required"><img src="<?php echo $current_url; ?>/images/pertanian.png" style="width: 30px; height: 30px;"/> Sawah</dt>
								<dd id="count-farmland"></dd>
								</dl>
							</div>
							<div class="mh-widget-col-1 mh-sidebar">
								<dl id="count-summary" class="larger">
								<dt class="required"><img src="<?php echo $current_url; ?>/images/perkebunan.png" style="width: 30px; height: 30px;"/> Kebun</dt>
								<dd id="count-orchard"></dd>
								</dl>
							</div>
						</div>
						<div class="clearfix" style="margin-bottom: 20px;">
							<h4 class="mh-widget-title">
								<span class="mh-widget-title-inner"><a class="mh-widget-title-link">Jalanan</a></span>
							</h4>
							<div class="mh-widget-col-1 mh-sidebar">
								<dl id="count-summary" class="larger">
									<dt class="required"><img src="<?php echo $current_url; ?>/images/asphalt.png" style="width: 30px; height: 30px;"/> Aspal</dt>
									<dd id="count-asphalt"></dd>
								</dl>
							</div>
							<div class="mh-widget-col-1 mh-sidebar">
								<dl id="count-summary" class="larger">
								<dt class="required"><img src="<?php echo $current_url; ?>/images/concrete.png" style="width: 30px; height: 30px;"/> Beton</dt>
								<dd id="count-concrete"></dd>
								</dl>
							</div>
							<div class="mh-widget-col-1 mh-sidebar">
								<dl id="count-summary" class="larger">
								<dt class="required"><img src="<?php echo $current_url; ?>/images/other.png" style="width: 30px; height: 30px;"/> Lainnya</dt>
								<dd id="count-other"></dd>
								</dl>
							</div>
							
							<div class="mh-widget-col-1 mh-sidebar" style="margin-top: 30px;">
								<dl id="count-summary" class="larger">
								<dt class="required"><img src="<?php echo $current_url; ?>/images/bridge.png" style="width: 30px; height: 30px;"/> Jembatan</dt>
								<dd id="count-bridge"></dd>
								</dl>
							</div>
						</div>
						<div class="clearfix" style="margin-bottom: 20px;">
							<h4 class="mh-widget-title">
								<span class="mh-widget-title-inner"><a class="mh-widget-title-link">Bangunan</a></span>
							</h4>
								<div class="mh-widget-col-1 mh-sidebar">
								<dl id="count-summary" class="larger">
									<dt class="required"><img src="<?php echo $current_url; ?>/images/tk.png" style="width: 30px; height: 30px;"/> TK</dt>
									<dd id="count-tk"></dd>
								</dl>
							</div>
							<div class="mh-widget-col-1 mh-sidebar">
								<dl id="count-summary" class="larger">
									<dt class="required"><img src="<?php echo $current_url; ?>/images/sd.png" style="width: 30px; height: 30px;"/> SD</dt>
									<dd id="count-sd"></dd>
								</dl>
							</div>
							<div class="mh-widget-col-1 mh-sidebar">
								<dl id="count-summary" class="larger">
								<dt class="required"><img src="<?php echo $current_url; ?>/images/smp.png" style="width: 30px; height: 30px;"/> SMP</dt>
								<dd id="count-smp"></dd>
								</dl>
							</div>
							<div class="mh-widget-col-1 mh-sidebar" style="margin-top: 30px;">
								<dl id="count-summary" class="larger">
								<dt class="required"><img src="<?php echo $current_url; ?>/images/sma.png" style="width: 30px; height: 30px;"/> SMA</dt>
								<dd id="count-sma"></dd>
								</dl>
							</div>
							<div class="mh-widget-col-1 mh-sidebar" style="margin-top: 30px;">
								<dl id="count-summary" class="larger">
								<dt class="required"><img src="<?php echo $current_url; ?>/images/pt.png" style="width: 30px; height: 30px;"/> Universitas</dt>
								<dd id="count-pt"></dd>
								</dl>
							</div>
							
							<div class="mh-widget-col-1 mh-sidebar" style="margin-top: 30px;">
								<dl id="count-summary" class="larger">
								<dt class="required"><img src="<?php echo $current_url; ?>/images/house.png" style="width: 25px; height: 25px;"/> Pemukiman</dt>
								<dd id="count-houses"></dd>
								</dl>
							</div>
						</div>
					</div>
				</div>
			</article>
		</div>
		
		<script type="text/javascript">
			var BIG = [{
						 "id":"network_transportation",
						 "label":"Jaringan Transportasi",
						 "attributeSets":{
							"highway":[
							   {
								  "key":"name",
								  "label":"Nama",
								  "type":"text"
							   },
							   {
								  "key":"lanes",
								  "label":"Jumlah Jalur",
								  "type":"text"
							   },
							   {
								  "key":"lit",
								  "label":"Diterangi?",
								  "type":"boolean"
							   },
							   {
								  "key":"width",
								  "label":"Lebar",
								  "type":"text"
							   },
							   {
								  "key":"surface",
								  "label":"Permukaan",
								  "type":"select",
								  "options":[
									 {
										"label":"Tanah",
										"value":"ground"
									 },
									 {
										"label":"Aspal",
										"value":"asphalt"
									 },
									 {
										"label":"Beton",
										"value":"concrete"
									 },
									 {
										"label":"Kerikil",
										"value":"gravel"
									 },
									 {
										"label":"Rumput",
										"value":"grass"
									 },
									 {
										"label":"Lumpur",
										"value":"mud"
									 },
									 {
										"label":"Pasir",
										"value":"sand"
									 },
									 {
										"label":"Kayu",
										"value":"wood"
									 }
								  ]
							   }
							]
						 },
						 "elements":[
							{
							   "label":"Jalan Kolektor",
							   "values":{
								  "highway":"secondary"
							   },
							   "attributeSetNames":[
								  "highway"
							   ],
							   "style":{
								  "cmykColor":[
									 0,
									 30,
									 0,
									 0
								  ],
								  "weight":5
							   }
							},
							{
							   "label":"Jalan Lokal",
							   "values":{
								  "highway":"local"
							   },
							   "attributeSetNames":[
								  "highway"
							   ],
							   "style":{
								  "cmykColor":[
									 0,
									 47,
									 60,
									 0
								  ],
								  "weight":3
							   }
							},
							{
							   "label":"Jalan Setapak",
							   "values":{
								  "highway":"path"
							   },
							   "attributeSetNames":[
								  "highway"
							   ],
							   "style":{
								  "cmykColor":[
									 0,
									 30,
									 0,
									 0
								  ],
								  "weight":3,
								  "dashArray":"5,5"
							   }
							},
							{
							   "label":"Jalan Pematang",
							   "values":{
								  "highway":"track"
							   },
							   "attributeSetNames":[
								  "highway"
							   ],
							   "style":{
								  "cmykColor":[
									 0,
									 30,
									 0,
									 0
								  ],
								  "weight":3
							   }
							},
							{
							   "label":"Jalan Tol",
							   "values":{
								  "highway":"motorway"
							   },
							   "attributeSetNames":[
								  "highway"
							   ],
							   "style":{
								  "cmykColor":[
									 0,
									 0,
									 60,
									 0
								  ],
								  "weight":5
							   }
							},
							{
							   "label":"Jalan Arteri",
							   "values":{
								  "highway":"trunk"
							   },
							   "attributeSetNames":[
								  "highway"
							   ],
							   "style":{
								  "cmykColor":[
									 0,
									 50,
									 0,
									 0
								  ],
								  "weight":5
							   }
							},
							{
							   "label":"Jembatan",
							   "values":{
								  "man_made":"bridge"
							   },
							   "attributeSetNames":[
								  "highway"
							   ],
							   "attributes":[
								  {
									 "key":"bridge:structure",
									 "label":"Struktur",
									 "type":"select",
									 "options":[
										{
										   "label":"Pelengkung (Arch)",
										   "value":"arch"
										},
										{
										   "label":"Alang (Beam)",
										   "value":"beam"
										},
										{
										   "label":"Rangka (Truss)",
										   "value":"truss"
										},
										{
										   "label":"Ponton",
										   "value":"floating"
										},
										{
										   "label":"Gantung",
										   "value":"suspension"
										},
										{
										   "label":"Kabel",
										   "value":"cable-stayed"
										}
									 ]
								  }
							   ],
							   "style":{
								  "cmykColor":[
									 0,
									 50,
									 0,
									 0
								  ],
								  "weight":5
							   }
							}
						 ]
					  },
					  {
						 "id":"boundary",
						 "label":"Batas Administrasi",
						 "attributeSets":{
							"boundary":[
							   {
								  "key":"name",
								  "label":"Nama",
								  "type":"text"
							   },
							   {
								  "key":"status",
								  "label":"Status",
								  "type":"select",
								  "options":[
									 {
										"label":"Definitif",
										"value":"definitive",
										"style": {
										  "dashArray":"0"
										}
									 },
									 {
										"label":"Indikatif",
										"value":"indicative",
										"style": {
										  "dashArray":"5,5"
										}
									 }
								  ]
							   }
							]
						 },
						 "elements":[
							{
							   "label":"Batas Desa",
							   "values":{
								  "admin_level":7
							   },
							   "attributeSetNames":[
								  "boundary"
							   ],
							   "style":{

							   }
							},
							{
							   "label":"Batas Dusun",
							   "values":{
								  "admin_level":8
							   },
							   "attributeSetNames":[
								  "boundary"
							   ],
							   "style":{

							   }
							},
							{
							   "label":"Batas RW",
							   "values":{
								  "admin_level":9
							   },
							   "attributeSetNames":[
								  "boundary"
							   ],
							   "style":{

							   }
							},
							{
							   "label":"Batas RT",
							   "values":{
								  "admin_level":10
							   },
							   "attributeSetNames":[
								  "boundary"
							   ],
							   "style":{

							   }
							}
						 ]
					  },
					  {
						 "id":"waters",
						 "label":"Perairan",
						 "attributeSets":{
							"waterway":[
							   {
								  "key":"width",
								  "label":"Lebar",
								  "type":"text"
							   },
							   {
								  "key":"name",
								  "label":"Nama",
								  "type":"text"
							   },
							   {
								  "key":"irrigation",
								  "label":"Untuk Irigasi?",
								  "type":"boolean"
							   },
							   {
								  "key":"intermittent",
								  "label":"Pasang Surut?",
								  "type":"boolean"
							   }
							]
						 },
						 "elements":[
							{
							   "label":"Sungai",
							   "values":{
								  "waterway":"river"
							   },
							   "attributeSetNames":[
								  "waterway"
							   ]
							},
							{
							   "label":"Kanal",
							   "values":{
								  "waterway":"canal"
							   },
							   "attributeSetNames":[
								  "waterway"
							   ]
							},
							{
							   "label":"Saluran",
							   "values":{
								  "waterway":"ditch"
							   },
							   "attributeSetNames":[
								  "waterway"
							   ]
							},
							{
							   "label":"Mata Air",
							   "values":{
								  "natural":"spring"
							   },
							   "attributes":[
								  {
									 "key":"drinking_water",
									 "label":"Air Minum?",
									 "type":"boolean"
								  },
								  {
									 "key":"name",
									 "label":"Nama",
									 "type":"text"
								  }
							   ]
							},
							{
							   "label":"Danau",
							   "values":{
								  "natural":"water",
								  "water":"lake"
							   },
							   "attributes":[
								  {
									 "key":"drinking_water",
									 "label":"Air Minum?",
									 "type":"boolean"
								  },
								  {
									 "key":"name",
									 "label":"Nama",
									 "type":"text"
								  },
								  {
									 "key":"intermittent",
									 "label":"Pasang Surut?",
									 "type":"boolean"
								  }
							   ]
							},
							{
							   "label":"Embung",
							   "values":{
								  "natural":"water",
								  "water":"basin",
								  "basin":"retention"
							   },
							   "attributes":[]
							},
							{
							  "label": "Sistem Pipa",
							  "values": {
								"waterway": "pipe_system"
							  },
							  "attributes": [{
								 "key": "width",
								 "label": "Lebar",
								 "type": "text"
							  }]
							}
						 ]
					  },
					  {
						 "id":"landuse",
						 "label":"Penggunaan Lahan",
						 "elements":[
							{
							   "label":"Pemukiman",
							   "values":{
								  "landuse":"residential"
							   },
							   "style":{
								  "rgbColor":[
									 215,
									 215,
									 215,
									 0
								  ],
								  "weight":2
							   }
							},
							{
							   "label":"Industri dan Pergudangan",
							   "values":{
								  "landuse":"industrial"
							   },
							   "style":{
								  "rgbColor":[
									 255,
									 175,
									 129,
									 0
								  ],
								  "weight":2
							   }
							},
							{
							   "label":"Perkantoran",
							   "values":{
								  "landuse":"commercial"
							   },
							   "style":{
								  "rgbColor":[
									 204,
									 150,
									 119,
									 0
								  ],
								  "weight":2
							   }
							},
							{
							   "label":"Pertokoan",
							   "values":{
								  "landuse":"retail"
							   },
							   "style":{
								  "rgbColor":[
									 251,
									 203,
									 217,
									 0
								  ],
								  "weight":2
							   }
							},
							{
							   "label":"Pemakaman",
							   "values":{
								  "landuse":"cemetery"
							   },
							   "style":{
								  "rgbColor":[
									 142,
									 142,
									 142,
									 0
								  ],
								  "weight":2
							   }
							},
							{
							   "label":"Perkebunan",
							   "values":{
								  "landuse":"orchard"
							   },
							   "attributes":[
								  {
									 "key":"crop",
									 "label":"Tanaman",
									 "type":"text"
								  }
							   ],
							   "style":{
								  "rgbColor":[
									 195,
									 240,
									 137,
									 0
								  ],
								  "weight":2
							   }
							},
							{
							   "label":"Hutan",
							   "values":{
								  "landuse":"forest"
							   },
							   "attributes":[
								  {
									 "key":"trees",
									 "label":"Pohon",
									 "type":"text"
								  }
							   ],
							   "style":{
								  "rgbColor":[
									 177,
									 211,
									 137,
									 0
								  ],
								  "weight":2
							   }
							},
							{
							   "label":"Sawah",
							   "values":{
								  "landuse":"farmland"
							   },
							   "attributes":[
								  {
									 "key":"crop",
									 "label":"Tanaman",
									 "type":"text"
								  }
							   ],
							   "style":{
								  "rgbColor":[
									 185,
									 220,
									 156,
									 0
								  ],
								  "weight":2
							   }
							},
							{
							   "label":"Rumput",
							   "values":{
								  "landuse":"grass"
							   },
							   "style":{
								  "rgbColor":[
									 188,
									 254,
									 159,
									 0
								  ],
								  "weight":2
							   }
							},
							{
							   "label":"Semak Belukar",
							   "values":{
								  "landuse":"meadow"
							   },
							   "style":{
								  "rgbColor":[
									 155,
									 231,
									 155,
									 0
								  ],
								  "weight":2
							   }
							},
							{
							   "label":"Rawa",
							   "values":{
								  "landuse":"wetland"
							   },
							   "style":{
								  "rgbColor":[
									 129,
									 204,
									 182,
									 0
								  ],
								  "weight":2
							   }
							},
							{
							   "label":"Lahan Terbuka",
							   "values":{
								  "landuse":"greenfield"
							   },
							   "style":{
								  "rgbColor":[
									 255,
									 255,
									 255,
									 0
								  ],
								  "weight":2
							   }
							}
						 ]
					  },
					  {
						 "id":"facilities_infrastructures",
						 "label":"Sarana Prasarana",
						 "elements":[
							{
							   "label":"Rumah",
							   "attributeKey": null,
							   "values":{
								  "building":"house"
							   },
							   "attributes":[
								  {
									 "key":"kk",
									 "label":"No KK",
									 "type":"penduduk_selector"
								  },
								  {
									 "key":"electricity_watt",
									 "label":"Watt Listrik",
									 "type": "date"
								  },
								  {
									 "key":"start_from",
									 "label":"Dari",
									 "type": "date"
								  }
							   ],
							   "style":{
								  "cmykColor":[
									 0,
									 30,
									 0,
									 0
								  ],
								  "weight":3
							   }
							},
							{
							   "label":"Sekolah",
							   "attributeKey": "isced",
							   "values":{
								  "amenity":"school"
							   },
							   "attributes":[
								  {
									 "key":"capacity",
									 "label":"Kapasitas",
									 "type":"text"
								  },
								  {
									 "key":"name",
									 "label":"Nama",
									 "type":"text"
								  },
								  {
									 "key":"address",
									 "label":"Alamat",
									 "type":"text"
								  },
								  {
									 "key":"isced",
									 "label":"Tingkat",
									 "type":"select",
									 "options":[
										{
										   "label":"PAUD/TK",
										   "value":0,
										   "marker":"ic_tk.png"
										},
										{
										   "label":"SD",
										   "value":1,
										   "marker":"ic_pendidikandasar.png"
										},
										{
										   "label":"SMP",
										   "value":2,
										   "marker":"ic_pendidikanmenengahpertama.png"
										},
										{
										   "label":"SMA",
										   "value":3,
										   "marker":"ic_pendidikanmenengahumum.png"
										},
										{
										   "label":"Univesitas/S1",
										   "value":4,
										   "marker":"ic_universitas.png"
										}
									 ]
								  }
							   ]
							},
							{
							   "label":"Tempat Ibadah",
							   "attributeKey": "building",
							   "values":{
								  "amenity":"place_of_worship"
							   },
							   "attributes":[
								  {
									 "key":"building",
									 "label":"Gedung",
									 "type":"select",
									 "options":[
										{
										   "label":"Masjid",
										   "value":"mosque",
										   "marker":"ic_mesjid.png"
										},
										{
										   "label":"Gereja",
										   "value":"church",
										   "marker":"ic_gereja.png"
										},
										{
										   "label":"Wihara",
										   "value":"vihara",
										   "marker":"ic_vihara.png"
										},
										{
										   "label":"Pura",
										   "value":"pura",
										   "marker":"ic_klenteng.png"
										}
									 ]
								  },
								  {
									 "key":"religion",
									 "label":"Agama",
									 "type":"select",
									 "options":[
										{
										   "label":"Islam",
										   "value":"islam"
										},
										{
										   "label":"Hindu",
										   "value":"hindu"
										},
										{
										   "label":"Buddha",
										   "value":"budhha"
										},
										{
										   "label":"Kristen",
										   "value":"chirstian"
										},
										{
										   "label":"Katolik",
										   "value":"catholic"
										}
									 ]
								  },
								  {
									 "key":"name",
									 "label":"Nama",
									 "type":"text"
								  }
							   ]
							},
							{
							   "label":"Sumur",
							   "attributeKey": null,
							   "values":{
								  "man_made":"waterwell"
							   },
							   "attributes":[
								  {
									 "key":"pump",
									 "label":"Pompa",
									 "type":"select",
									 "options":[
										{
										   "label":"Bertenaga",
										   "value":"powered"
										},
										{
										   "label":"Manual",
										   "value":"manual"
										}
									 ]
								  },
								  {
									 "key":"drinking_water",
									 "label":"Air Minum",
									 "type":"boolean"
								  }
							   ]
							},
							{
							   "label":"MCK Umum",
							   "attributeKey": null,
							   "values":{
								  "value":"toilets",
								  "access":"public"
							   }
							},
							{
							   "label":"Lapangan Olahraga",
							   "attributeKey": null,
							   "values":{
								  "leisure":"pitch"
							   },
							   "attributes":[
								  {
									 "key":"sport",
									 "label":"Olahraga",
									 "type":"select",
									 "options":[
										{
										   "label":"Sepak Bola",
										   "value":"soccer"
										},
										{
										   "label":"Basket",
										   "value":"basketball"
										},
										{
										   "label":"Badminton",
										   "value":"badminton"
										},
										{
										   "label":"Voli",
										   "value":"volleyball"
										}
									 ]
								  },
								  {
									 "key":"surface",
									 "label":"Permukaan",
									 "type":"select",
									 "options":[
										{
										   "label":"Tanah",
										   "value":"earth"
										},
										{
										   "label":"Beton",
										   "value":"concrete"
										}
									 ]
								  }
							   ]
							},
							{
							   "label":"Pasar",
							   "attributeKey": null,
							   "values":{
								  "amenity":"marketplace"
							   },
							   "attributes":[
								  {
									 "key":"opening_hours",
									 "label":"Jam Buka",
									 "type":"time"
								  },
								  {
									 "key":"name",
									 "label":"Nama",
									 "type":"text"
								  }
							   ]
							},
							{
							   "label":"Pembangkit Listrik",
							   "attributeKey": null,
							   "values":{
								  "power":"plant"
							   },
							   "attributes":[
								  {
									 "key":"name",
									 "label":"Nama",
									 "type":"text"
								  },
								  {
									 "key":"output",
									 "label":"Pengeluaran",
									 "type":"text"
								  },
								  {
									 "key":"source",
									 "label":"Sumber",
									 "type":"select",
									 "options":[
										{
										   "label":"Batu Bara",
										   "value":"coal"
										},
										{
										   "label":"Gas",
										   "value":"gas"
										},
										{
										   "label":"Air",
										   "value":"water"
										},
										{
										   "label":"Panas Bumi",
										   "value":"geothermal"
										},
										{
										   "label":"Minyak",
										   "value":"oil"
										}
									 ]
								  }
							   ]
							}
						 ]
					  }
				]
			$(document).ready(() => {
				const SERVER = 'http://api.tatakelola.sideka.id';

				$.ajax(SERVER + '/geojsons/region/32.06.19.2009', { 
					method: 'GET',
					dataType: 'json',
					success: setupMap
				});
				
				$.ajax(SERVER + '/summaries/region/32.06.19.2009', { 
					method: 'GET',
					dataType: 'json',
					success: setupData
				});
			});
			
			function setupMap(response) {
				let width = 800
				let height = 400;
				let svg = d3.select(".svg-container").append("svg").attr("width", width).attr("height", height);
				let geoJson = { type: "featureCollection",  
								crs: { "type": "name", "properties": { "name": "urn:ogc:def:crs:OGC:1.3:CRS84" }}, features: [] };
				
				response[1].data.features.forEach(feature => {
					feature['indicator'] = response[1].type;
				});
				
				response[2].data.features.forEach(feature => {
					feature['indicator'] = response[2].type;
				});
				
				response[3].data.features.forEach(feature => {
					feature['indicator'] = response[3].type;
				});
				
				response[4].data.features.forEach(feature => {
					feature['indicator'] = response[4].type;
				});
				
				response[5].data.features.forEach(feature => {
					feature['indicator'] = response[5].type;
				});

				geoJson.features = geoJson.features.concat(response[4].data.features);
				geoJson.features = geoJson.features.concat(response[5].data.features);
				geoJson.features = geoJson.features.concat(response[1].data.features);
				geoJson.features = geoJson.features.concat(response[3].data.features);
				
				let circumference = 0;
				
				for (let i=0; i<response[4].data.features.length; i++) {
					let boundaryPolygon = turf.polygon(response[4].data.features[i].geometry.coordinates);
					let line = turf.polygonToLineString(boundaryPolygon);
					circumference += turf.length(line, {units: 'kilometers'});
				}
		
				$('#boundary-circumference')[0].innerText = Math.ceil(circumference) + ' km';
				
				let projection = d3.geo.mercator().scale(1).translate([0, 0]);
				let path = d3.geo.path().projection(projection);
				
				let bounds = path.bounds(geoJson.features[0]);
				
				let scale = .95 / Math.max((bounds[1][0] - bounds[0][0]) / width,
					(bounds[1][1] - bounds[0][1]) / height);
					
			    let transl = [(width - scale * (bounds[1][0] + bounds[0][0])) / 2,
					(height - scale * (bounds[1][1] + bounds[0][1])) / 2];

				projection.scale(scale).translate(transl);
				
				for (let i=0; i<geoJson.features.length; i++) {
					let feature = geoJson.features[i];
					
					let indicator = BIG.filter(e => e.id === feature.indicator)[0];
					 
					if(!indicator)
					  continue;

					let keys = Object.keys(feature.properties);

					if(keys.length === 0){
						svg.append("path").attr("d", path(feature)).style("fill", "transparent").style("stroke", "steelblue");
						continue;
					}
					
					for(let j=0; j<keys.length; j++){
						let element = indicator.elements.filter(e => e.values[keys[j]] === feature['properties'][keys[j]])[0];
						
						if(!element){
						  svg.append("path").attr("d", path(feature)).style("fill", "transparent").style("stroke", "steelblue");
						  continue;
						}
						
						let color = 'steelblue';
						let icon = null;
						
						if(element['style'])
						   color = getStyleColor(element['style'], '#ffffff');
						 
						if(!element || !element['style']){
							svg.append("path").attr("d", path(feature)).style("fill", "transparent").style("stroke", "steelblue");
							  continue;
						}

						let dashArray = element['style']['dashArray'] ? element['style']['dashArray'] : null;

						if(indicator.id == 'network_transportation'){
							svg.append("path").attr("d", path(feature)).style("fill", "transparent").style("stroke", color).style("stroke-dasharray", dashArray);
						} 
						else{
							svg.append("path").attr("d", path(feature)).style("fill", color).style("stroke", color);
						}
					}
				}
			}
			
			function setupData(response) {
				let data = response[0];
				$('#boundary-area')[0].innerText = Math.ceil(data.pemetaan_desa_boundary / 10000) + ' ha';
				$('#road-length')[0].innerText = Math.ceil(data.pemetaan_highway_asphalt_length + data.pemetaan_highway_concrete_length + data.pemetaan_highway_other_length + data.pemetaan_bridge_length) + ' km';
				
				$('#count-forest')[0].innerText = Math.ceil(data.pemetaan_landuse_farmland_area / 10000) + ' ha';
				$('#count-farmland')[0].innerText = Math.ceil(data.pemetaan_landuse_forest_area / 10000) + ' ha';
				$('#count-orchard')[0].innerText = Math.ceil(data.pemetaan_landuse_orchard_area / 10000) + ' ha';
				
				$('#count-asphalt')[0].innerText = Math.ceil(data.pemetaan_highway_asphalt_length) + ' Km';
				$('#count-concrete')[0].innerText = Math.ceil(data.pemetaan_highway_concrete_length) + ' Km';
				$('#count-other')[0].innerText = Math.ceil(data.pemetaan_highway_other_length) + ' Km';
				$('#count-bridge')[0].innerText = Math.ceil(data.pemetaan_bridge_length) + ' Km';
				
				$('#count-tk')[0].innerText = data.pemetaan_school_tk + ' Gedung';
				$('#count-sd')[0].innerText = data.pemetaan_school_sd + ' Gedung';
				$('#count-smp')[0].innerText = data.pemetaan_school_smp + ' Gedung';
				$('#count-sma')[0].innerText = data.pemetaan_school_sma + ' Gedung';
				$('#count-pt')[0].innerText = data.pemetaan_school_pt + ' Gedung';

				$('#count-houses')[0].innerText = 0 + ' Gedung';
			}
			
			function setupStyle(configStyle){
				let resultStyle = Object.assign({}, configStyle);
				let color = this.getStyleColor(configStyle);
				if(color)
					resultStyle['color'] = color;
				return resultStyle;
			}

			function getStyleColor(configStyle, defaultColor=null){
				if(configStyle['cmykColor'])
					return this.cmykToRgbString(configStyle['cmykColor']);
				if(configStyle['rgbColor'])
					return this.rgbToRgbString(configStyle['rgbColor']);
				return defaultColor;
			}
			
			function cmykToRgbString(cmyk) {
				let c = cmyk[0], m = cmyk[1], y = cmyk[2], k = cmyk[3];
				let r, g, b;
				r = 255 - ((Math.min(1, c * (1 - k) + k)) * 255);
				g = 255 - ((Math.min(1, m * (1 - k) + k)) * 255);
				b = 255 - ((Math.min(1, y * (1 - k) + k)) * 255);
				return "rgb(" + r + "," + g + "," + b + ")";
			}
			
			function rgbToRgbString(rgb) {
				let r = rgb[0], g = rgb[1], b = rgb[2];
				return "rgb(" + r + "," + g + "," + b + ")";
			}
		</script>
	
<?php } else { ?>

<p>Data pemetaan belum diunggah</p>

<?php } ?>


