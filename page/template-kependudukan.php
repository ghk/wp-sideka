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

$ckan_host = "http://data.sideka.id";
#$ckan_host = "http://ckan.neon.microvac:5000";
$package_id = $desa_id."-kependudukan";
$json = @file_get_contents($ckan_host . '/api/3/action/package_show?id=' . $package_id);
$package_exists = json_decode($json)->success;
?>

<?php if($package_exists) { ?>
    <style>
		.pdd {
            width: 100%;
            margin-bottom: 50px;
			display: flex;
            justify-content: space-around;
		}

        .pdd--width-100\% {
            width: 100%
        }

        .pdd--row {
            flex-flow: row wrap;
        }

        .pdd--column {
            flex-flow: column wrap;
        }

        .pdd--align-center {
            align-items: center;
        }

        .pdd--align-baseline {
            align-items: baseline;
        }

        .pdd__info-age, .pdd__info-total, .pdd__info-gender, .pdd__info-total-circle,
        .pdd__age-content, .pdd__job-content, .pdd__edu-content, .pdd__stat-content {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .pdd__info-total {
            margin-bottom: 1.25em;
        }

        .pdd__info-total-circle {
            background-color: #fe8b6e;
            border-radius: 50%;
            width: 120px;
            height: 120px;
            color: #fff;
        }
        
        @media screen and (min-width: 769px) {
            .pdd__age-graph {
                width: 50%;
                margin-right: 25px;
            }

            .pdd__job-graph {
                width: 60%;
                margin-right: 25px;
            }

            .pdd__edu-graph {
                width: 60%;
                margin-right: 25px;
            }

            .pdd__stat-graph {
                width: 55%;
                margin-left: 10px;
                margin-right: 10px;
            }
        }

        .pdd__image--50 {
            width: 50px;
        } 

        .pdd__image--75 {
            width: 75px;
        } 

        .pdd__image--100 {
            width: 100px;
        }

        .pdd__table td {
            vertical-align: middle;
        }

        .pdd table, .pdd table tr, .pdd table td {
            border: none;
        }
    </style>
	
	<div class="pdd pdd--row pdd--align-baseline">	
        <div class="pdd__info-age pdd--column">
            <h4>Berdasarkan Usia</h4>
            <table class="pdd__table">
                <tr class="subusia">
                    <td class="pdd__info-age-below-15"></td>
                    <td><img class="pdd__image--50" src="/wp-content/plugins/sideka/page/images/kependudukan/child.png"></td>
                    <td class="usiastats"><a>berusia di bawah <br><strong>15 tahun</strong></a></td>
                </tr>
                <tr class="subusia">
                    <td class="pdd__info-age-below-65"></td>
                    <td><img class="pdd__image--50" src="/wp-content/plugins/sideka/page/images/kependudukan/adult.png"></td>
                    <td class="usiastats"><a>berusia antara <br><strong>15-65 tahun</strong></a></td>
                </tr>
                <tr class="subusia">
                    <td class="pdd__info-age-above-65"></td>
                    <td><img class="pdd__image--50" src="/wp-content/plugins/sideka/page/images/kependudukan/elder.png"></td>
                    <td class="usiastats"><a>berusia di atas <br><strong>65 tahun</strong></a></td>
                </tr>
            </table>
        </div>
        <div class="pdd__info-total pdd--column">
            <h4>TOTAL</h4>
            <div class="pdd__info-total-circle pdd--column">
                <span class="pdd__info-total-label"></span>
                <span>jiwa</span>
            </div>
        </div>
        <div class="pdd__info-gender pdd--column">
            <h4>Berdasarkan Gender</h4>
            <div class="pdd__info-gender-graph">
                <canvas id="pdd__info-gender-graph"></canvas>
            </div>            
        </div>
	</div>

    <div class="pdd pdd--column pdd--align-center">
        <div class="pdd__age-title">
            <h1>KELOMPOK USIA</h1>
        </div>        
        <div class="pdd__age-content pdd--row pdd--width-100%">           
            <div class="pdd__age-graph"> 
                <canvas id="pdd__age-graph"></canvas>
            </div>             
            <div class="pdd__age-table">
                <table class="pdd__table">
                    <tr class="usia-1">
                        <td>
                            <img class="pdd__image--100" src="/wp-content/plugins/sideka/page/images/kependudukan/deficit.png">
                        </td>
                        <td>
                            <span class="pdd__age-productive"></span>
                            <span>berada di usia <br><strong>produktif</strong></span>
                        </td>
                    </tr>
                    <tr class="usia-2">
                        <td>
                            <img class="pdd__image--100" src="/wp-content/plugins/sideka/page/images/kependudukan/surplus.png">
                        </td>
                        <td>
                            <span class="pdd__age-unproductive"></span>
                            <span>berada di usia <br><strong>tidak produktif</strong></span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="pdd pdd--column pdd--align-center">
        <div class="pdd__job-title">
            <h1>PEKERJAAN</h1>
        </div>        
        <div class="pdd__job-content pdd--row pdd--width-100%">
            <div class="pdd__job-graph">
                <canvas id="pdd__job-graph"></canvas>
            </div>
            <div class="pdd__job-table">
                <table class="pdd__table">
                    <tr>
                        <td>
                            <img class="pdd__image--100" src="/wp-content/plugins/sideka/page/images/kependudukan/housewife.png">
                        </td>
                        <td>
                            <span class="pdd__job-majority-female-percentage"></span>
                            <span>mayoritas perempuan <br/><strong class="pdd__job-majority-female"></strong></span>
                        </td>
                    </tr>
                    <tr>
                        <td><img class="pdd__image--100" src="/wp-content/plugins/sideka/page/images/kependudukan/laborer.png"></td>
                        <td>
                            <span class="pdd__job-majority-male-percentage"></span>
                            <span>mayoritas laki-laki <br/><strong class="pdd__job-majority-male"></strong></span>
                        </td>
                    </tr>
                    <tr>
                        <td><img class="pdd__image--100" src="/wp-content/plugins/sideka/page/images/kependudukan/student.png"></td>
                        <td>
                            <span class="pdd__job-majority-total-percentage"></span>
                            <span>di antaranya <br/><strong class="pdd__job-majority-total"></strong></span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="pdd pdd--column pdd--align-center">
        <div class="pdd__edu-title">
            <h1>PENDIDIKAN</h1>
        </div>        
        <div class="pdd__edu-content pdd--row">
            <div class="pdd__edu-graph">
                <canvas id="pdd__edu-graph"></canvas>
            </div>
            <div class="pdd__edu-table">
                <table class="pdd__table">
                    <tr class="study-1">
                        <td><img class="pdd__image--100" src="/wp-content/plugins/sideka/page/images/kependudukan/9years.png"></td>
                        <td>
                            <span class="pdd__edu-9years-percentage"></span>
                            <span>telah menyelesaikan <br><strong>program wajib belajar 9 tahun</strong></span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="pdd pdd--column pdd--align-center">
        <div class="pdd__stat-title">
            <h1>STATUS & AGAMA</h1>
        </div>
        <div class="pdd__stat-content pdd--row">
            <div class="pdd__stat-status-table">
                <table class="pdd__table">
                    <tr>
                        <td>
                            <span class="pdd__stat-status-married-percentage"></span>
                            <span><strong>sudah menikah</strong></span>
                        </td>
                        <td><img class="pdd__image--75" src="/wp-content/plugins/sideka/page/images/kependudukan/married.png"></td>
                    </tr>
                    <tr>
                        <td>
                            <span class="pdd__stat-status-single-percentage"></span>
                            <span><strong>belum menikah</strong></span>
                        </td>
                        <td><img class="pdd__image--75" src="/wp-content/plugins/sideka/page/images/kependudukan/single.png"></td>
                    </tr>
                    <tr>
                        <td>
                            <span class="pdd__stat-status-widowed-percentage"></span>
                            <span><strong>janda/duda</strong></span>
                        </td>
                        <td><img class="pdd__image--75" src="/wp-content/plugins/sideka/page/images/kependudukan/widowed.png"></td>
                    </tr>
                </table>
            </div>
            <div class="pdd__stat-graph pdd--column">
                <canvas id="pdd__stat-status-graph"></canvas>
                <canvas id="pdd__stat-religion-graph"></canvas>
            </div>
            <div class="pdd__stat-religion-table">
                <table class="pdd__table">
                    <tr class="pdd__stat-religion-islam">
                        <td><img class="pdd__image--75" src="/wp-content/plugins/sideka/page/images/kependudukan/islam.png"></td>
                        <td>
                            <span class="pdd__stat-religion-islam-percentage"></span>
                            <span><strong>Islam</strong></span>
                        </td>
                    </tr>
                    <tr class="pdd__stat-religion-kristen">
                        <td><img class="pdd__image--75" src="/wp-content/plugins/sideka/page/images/kependudukan/protestan.png"></td>
                        <td>
                            <span class="pdd__stat-religion-kristen-percentage"></span>
                            <span><strong>Kristen</strong></span>
                        </td>
                    </tr>
                    <tr class="pdd__stat-religion-katolik">
                        <td><img class="pdd__image--75" src="/wp-content/plugins/sideka/page/images/kependudukan/catholic.png"></td>
                        <td>
                            <span class="pdd__stat-religion-katolik-percentage"></span>
                            <span><strong>Katolik</strong></span>
                        </td>
                    </tr>
                    <tr class="pdd__stat-religion-hindu">
                        <td><img class="pdd__image--75" src="/wp-content/plugins/sideka/page/images/kependudukan/hindu.png"></td>
                        <td>
                            <span class="pdd__stat-religion-hindu-percentage"></span>
                            <span><strong>Hindu</strong></span>
                        </td>
                    </tr>
                    <tr class="pdd__stat-religion-buddha">
                        <td><img class="pdd__image--75" src="/wp-content/plugins/sideka/page/images/kependudukan/buddha.png"></td>
                        <td>
                            <span class="pdd__stat-religion-buddha-percentage"></span>
                            <span><strong>Buddha</strong></span>
                        </td>
                    </tr>
                    <tr class="pdd__stat-religion-konghucu">
                        <td><img class="pdd__image--75" src="/wp-content/plugins/sideka/page/images/kependudukan/buddha.png"></td>
                        <td>
                            <span class="pdd__stat-religion-konghucu-percentage"></span>
                            <span><strong>Konghucu</strong></span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <script src="/wp-content/plugins/sideka/Chart.min.js"></script>
    <script src="/wp-content/plugins/sideka/chartjs-plugin-datalabels.min.js"></script>
    <script src="/wp-content/plugins/sideka/d3.v3.js"></script>

    <script type="text/javascript">
        Chart.defaults.global.plugins.datalabels.display = false

        let header = document.getElementsByClassName("entry-header")[0];
        header && header.remove();
        let package_id = "<?= $package_id ?>";
        let ckan_host = "<?= $ckan_host ?>";
        let package = <?= $json ?>;
        
        let umur = package.result.resources.filter(function(r) {return r.name === "Kelompok Umur Berdasarkan Jenis Kelamin"})[0];
        d3.csv(ckan_host + umur.url, function(error, data) {
            let ageData = transformDataAge(data);

            let labels = Object.keys(ageData['ageGroup']);
            groupLabels = labels.filter(function(label) { return label !== '15-65'});

            let maleData = groupLabels.map(function(key) { return ageData['ageGroup'][key]['Laki-laki']; })
            let maleTotal = maleData.reduce(function(acc, curVal) { return acc + curVal });
            let femaleData = groupLabels.map(function(key) { return ageData['ageGroup'][key]['Perempuan']; })
            let femaleTotal = femaleData.reduce(function(acc, curVal) { return acc + curVal });
            let unknownData = groupLabels.map(function(key) { return (ageData['ageGroup'][key][''] ? ageData['ageGroup'][key][''] : 0); })
            let unknownTotal = unknownData.reduce(function(acc, curVal) { return acc + curVal });

            document.getElementsByClassName('pdd__info-age-below-15')[0].innerHTML = 
                Math.round((ageData['ageGroup']['0-15']['total'] / ageData['total'] * 100)) + '%';
            document.getElementsByClassName('pdd__info-age-below-65')[0].innerHTML =             
                Math.round((ageData['ageGroup']['15-65']['total'] / ageData['total'] * 100)) + '%';
            document.getElementsByClassName('pdd__info-age-above-65')[0].innerHTML = 
                Math.round((ageData['ageGroup']['>65']['total'] / ageData['total'] * 100)) + '%';
            document.getElementsByClassName('pdd__info-total-label')[0].innerHTML = ageData['total'].toLocaleString();
             
            document.getElementsByClassName('pdd__age-productive')[0].innerHTML = 
                Math.round((ageData['ageGroup']['15-65']['total'] / ageData['total'] * 100)) + '%';
            document.getElementsByClassName('pdd__age-unproductive')[0].innerHTML = 
                100 - Math.round((ageData['ageGroup']['15-65']['total'] / ageData['total'] * 100)) + '%';               

            var ageChartData = {
                labels: groupLabels,
                datasets: [{
                    label: 'Laki-laki',
                    backgroundColor: 'rgb(84, 122, 136)',
                    stack: 'Stack 0',
                    data: maleData
                }, {
                    label: 'Perempuan',
                    backgroundColor: 'rgb(182, 192, 158)',
                    stack: 'Stack 1',
                    data: femaleData
                }, {
                    label: 'Tidak Diketahui',
                    backgroundColor: 'rgb(0, 0, 0)',
                    stack: 'Stack 2',
                    data: unknownData
                }]
            };		
			var ageCtx = document.getElementById('pdd__age-graph').getContext('2d');
			new Chart(ageCtx, {
				type: 'bar',
				data: ageChartData,
				options: {
					title: { display: false, text: 'Chart Kelompok Usia' },
					tooltips: { mode: 'index', intersect: false },
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					}
				}
			});  

            var genderChartData = {
                labels: ['\uf222', '\uf221', '\uf128'],
                datasets: [{
                    label: 'Dataset',
                    data: [Math.round(maleTotal / ageData['total'] * 100), Math.round(femaleTotal / ageData['total'] * 100), Math.round(unknownTotal / ageData['total'] * 100)],
                    backgroundColor: ['rgb(86, 132, 255)', 'rgb(244, 102, 77)', 'gray']
                }]
            };
            var genderCtx = document.getElementById('pdd__info-gender-graph').getContext('2d');
            new Chart(genderCtx, {
                type: 'horizontalBar',
                data: genderChartData,
                options: {
					title: { display: false, text: 'Chart Gender' },
                    legend: { display: false },
					tooltips: { enabled: false },
                    scales: {                       
                        yAxes: [{ 
                            ticks: { fontFamily: "'FontAwesome'", fontSize: 18 },
                            gridLines: { display: false, drawBorder: false },
                        }],
                        xAxes: [{
                            ticks: { display: false },
                            gridLines: { display: false, drawBorder: false },
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display: true,
                            color: 'white',
                            formatter: function(value, context) { 
                                let append = '';
                                if (context.dataIndex === 0) { append = 'Laki-laki' } 
                                else if (context.dataIndex === 1) { append = 'Perempuan' }                                                                
                                return append ? append + ' (' + value + '%)' : value + '%';
                            }
                        }
                    }
				}
            })
        });

        let pekerjaan = package.result.resources.filter(function(r) {return r.name === "Pekerjaan Berdasarkan Jenis Kelamin"})[0];
        d3.csv(ckan_host + pekerjaan.url, function(error, csv) {
            let data = transformLabeledData(csv, 'pekerjaan', true);
            let labels = Object.keys(data['pekerjaan']);

            let maleData = labels.map(function(key) { return data['pekerjaan'][key]['Laki-laki']; })
            let maleTotal = maleData.reduce(function(acc, curVal) { return acc + curVal });
            let maleMajorityDataLabel = labels.reduce(function(m, k) { 
                if (m === 'LAIN-LAIN' && labels.length > 1) { return labels[1]; }
                return data['pekerjaan'][k]['Laki-laki'] > data['pekerjaan'][m]['Laki-laki'] ? k : m;
            }, labels[0]);    
            
            let femaleData = labels.map(function(key) { return data['pekerjaan'][key]['Perempuan']; })
            let femaleTotal = femaleData.reduce(function(acc, curVal) { return acc + curVal });
            let femaleMajorityDataLabel = labels.reduce(function(m, k) { 
                if (m === 'LAIN-LAIN' && labels.length > 1) { return labels[1]; }
                return data['pekerjaan'][k]['Perempuan'] > data['pekerjaan'][m]['Perempuan'] ? k : m;
            }, labels[0]);            
            
            let totalMajorityDataLabel = labels.reduce(function(m, k) { 
                if (m === 'LAIN-LAIN' && labels.length > 1) { return labels[1]; }
                return data['pekerjaan'][k]['total'] > data['pekerjaan'][m]['total'] ? k : m;
            }, labels[0]);    

            let unknownData = labels.map(function(key) { return (data['pekerjaan'][key][''] ? data['pekerjaan'][key][''] : 0); })
            let unknownTotal = unknownData.reduce(function(acc, curVal) { return acc + curVal });

            document.getElementsByClassName('pdd__job-majority-female')[0].innerHTML = femaleMajorityDataLabel;
            document.getElementsByClassName('pdd__job-majority-female-percentage')[0].innerHTML = 
                Math.round((data['pekerjaan'][femaleMajorityDataLabel]['Perempuan'] / femaleTotal * 100)) + '%';
            document.getElementsByClassName('pdd__job-majority-male')[0].innerHTML = maleMajorityDataLabel;
            document.getElementsByClassName('pdd__job-majority-male-percentage')[0].innerHTML =             
                Math.round((data['pekerjaan'][maleMajorityDataLabel]['Laki-laki'] / maleTotal * 100)) + '%';
            document.getElementsByClassName('pdd__job-majority-total')[0].innerHTML = totalMajorityDataLabel;
            document.getElementsByClassName('pdd__job-majority-total-percentage')[0].innerHTML = 
                Math.round((data['pekerjaan'][totalMajorityDataLabel]['total'] / data['total'] * 100)) + '%';
            

            var jobChartData = {
                labels: labels,
                datasets: [{
                    label: 'Laki-laki',
                    backgroundColor: 'rgb(123, 82, 162)',
                    stack: 'Stack 0',
                    data: maleData
                }, {
                    label: 'Perempuan',
                    backgroundColor: 'rgb(247, 156, 150)',
                    stack: 'Stack 1',
                    data: femaleData
                }, {
                    label: 'Tidak Diketahui',
                    backgroundColor: 'rgb(0, 0, 0)',
                    stack: 'Stack 2',
                    data: unknownData
                }]
            };		
			var jobCtx = document.getElementById('pdd__job-graph').getContext('2d');
			new Chart(jobCtx, {
				type: 'horizontalBar',
				data: jobChartData,
				options: {
					title: { display: false, text: 'Chart Pekerjaan' },
					tooltips: { mode: 'index', intersect: false },
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					}
				}
			});  
        });

        let pendidikanGroups = {            
            "Belum/Tidak Sekolah": ['Belum masuk TK/Kelompok Bermain', 'Tidak dapat membaca dan menulis huruf Latin/Arab' ,'Tidak pernah sekolah', 'Tidak tamat SD/sederajat '],
            "Sedang TK/SD": ['Sedang SD/sederajat'],
            "Tamat SD": ['Tamat SD/sederajat','Sedang SLTP/sederajat'],
            "Tamat SLTP": ['Tamat SLTP/sederajat','Sedang SLTA/sederajat'],
            "Tamat SLTA": ['Tamat SLTA/sederajat','Sedang D-1/sederajat', 'Sedang D-2/sederajat', 'Sedang D-3/sederajat', 'Sedang D-4/sederajat', 'Sedang S-1/sederajat'],
            "Tamat PT": ['Tamat D-1/sederajat', 'Tamat D-2/sederajat', 'Tamat D-3/sederajat', 'Tamat D-4/sederajat','Tamat S-1/sederajat', 'Sedang S-2/sederajat', 'Tamat S-2/sederajat', 'Sedang S-3/sederajat', 'Tamat S-3/sederajat'],
            "Tidak Diketahui": ['Tidak Diketahui']
        }

        let pendidikan = package.result.resources.filter(function(r) {return r.name === "Pendidikan Berdasarkan Jenis Kelamin"})[0];
        d3.csv(ckan_host + pendidikan.url, function(error, csv) {
            let data = transformLabeledData(csv, 'pendidikan', false);
            //let labels = Object.keys(data['pendidikan']);
            let labels = Object.keys(pendidikanGroups).map(function(group) { return group.toUpperCase(); });

            let maleData = labels.map(function(key) { return data['pendidikan'][key]['Laki-laki']; })
            let maleTotal = maleData.reduce(function(acc, curVal) { return acc + curVal });
            let femaleData = labels.map(function(key) { return data['pendidikan'][key]['Perempuan']; })
            let femaleTotal = femaleData.reduce(function(acc, curVal) { return acc + curVal });
            let unknownData = labels.map(function(key) { return (data['pendidikan'][key][''] ? data['pendidikan'][key][''] : 0); })
            let unknownTotal = unknownData.reduce(function(acc, curVal) { return acc + curVal });

            document.getElementsByClassName('pdd__edu-9years-percentage')[0].innerHTML = 
                Math.round((data['pendidikan']['TAMAT SLTP']['total'] / data['total'] * 100)) + '%';

            var eduChartData = {
                labels: labels,
                datasets: [{
                    label: 'Laki-laki',
                    backgroundColor: 'rgb(123, 82, 162)',
                    stack: 'Stack 0',
                    data: maleData
                }, {
                    label: 'Perempuan',
                    backgroundColor: 'rgb(247, 156, 150)',
                    stack: 'Stack 1',
                    data: femaleData
                }, {
                    label: 'Tidak Diketahui',
                    backgroundColor: 'rgb(0, 0, 0)',
                    stack: 'Stack 2',
                    data: unknownData
                }]
            };		
			var eduCtx = document.getElementById('pdd__edu-graph').getContext('2d');
			new Chart(eduCtx, {
				type: 'horizontalBar',
				data: eduChartData,
				options: {
					title: { display: false, text: 'Chart Pendidikan' },
					tooltips: { mode: 'index', intersect: false },
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					}
				}
			});  
        });

        let statusKawin = package.result.resources.filter(function(r) {return r.name === "Status Kawin Berdasarkan Jenis Kelamin"})[0];
        d3.csv(ckan_host + statusKawin.url, function(error, csv) {
            let statusData = transformLabeledData(csv, 'status_kawin', false);

            if (!('BELUM KAWIN' in statusData['status_kawin'])) { statusData['agstatus_kawinama']['BELUM KAWIN'] = { 'total': 0 }; }
            if (!('KAWIN' in statusData['status_kawin'])) { statusData['status_kawin']['KAWIN'] = { 'total': 0 }; }
            if (!('JANDA/DUDA' in statusData['status_kawin'])) { statusData['status_kawin']['JANDA/DUDA'] = { 'total': 0 }; }
            if (!('TIDAK DIKETAHUI' in statusData['status_kawin'])) { statusData['status_kawin']['TIDAK DIKETAHUI'] = { 'total': 0 }; }

            let statusLabels = ['BELUM KAWIN', 'KAWIN', 'JANDA/DUDA', 'TIDAK DIKETAHUI'];
            let statusDatas = statusLabels.map(function(key) { return statusData['status_kawin'][key]['total']; })              
            
            document.getElementsByClassName('pdd__stat-status-married-percentage')[0].innerHTML = 
                Math.round((statusData['status_kawin']['KAWIN']['total'] / statusData['total'] * 100)) + '%';
            document.getElementsByClassName('pdd__stat-status-single-percentage')[0].innerHTML =             
                Math.round((statusData['status_kawin']['BELUM KAWIN']['total'] / statusData['total'] * 100)) + '%';
            document.getElementsByClassName('pdd__stat-status-widowed-percentage')[0].innerHTML = 
                Math.round((statusData['status_kawin']['JANDA/DUDA']['total'] / statusData['total'] * 100)) + '%';

            var statusChartData = {   
                labels: statusLabels,                 
                datasets: [{     
                    data: statusDatas,
                    backgroundColor: ['rgb(246, 217, 161)', 'rgb(139, 181, 229)', 'rgb(212, 186, 218)', 'rgb(96, 190, 190)', 'rgb(91, 155, 234)', 'grey']
                }]
            };		

            var statusCtx = document.getElementById('pdd__stat-status-graph').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: statusChartData,
                options: {
                    title: { display: false, text: 'Chart Status' },
                    legend: { display: false },
                    tooltips: { mode: 'index', intersect: false },                        
                    plugins: {
                        datalabels: {
                            display: true,
                            color: 'white'                                
                        }
                    }
                }
            });  
        });   

        
        let agama = package.result.resources.filter(function(r) {return r.name === "Agama Berdasarkan Jenis Kelamin"})[0];
        d3.csv(ckan_host + agama.url, function(error, csv) {
            let religionData = transformLabeledData(csv, 'agama', false);
                            
            if (!('ISLAM' in religionData['agama'])) { religionData['agama']['ISLAM'] = { 'total': 0 }; }
            if (!('KRISTEN' in religionData['agama'])) { religionData['agama']['KRISTEN'] = { 'total': 0 }; }
            if (!('KATHOLIK' in religionData['agama'])) { religionData['agama']['KATHOLIK'] = { 'total': 0 }; }
            if (!('HINDU' in religionData['agama'])) { religionData['agama']['HINDU'] = { 'total': 0 }; }
            if (!('BUDHA' in religionData['agama'])) { religionData['agama']['BUDHA'] = { 'total': 0 }; }
            if (!('KONGHUCU' in religionData['agama'])) { religionData['agama']['KONGHUCU'] = { 'total': 0 }; }

            let religionLabels = ['ISLAM', 'KRISTEN', 'KATHOLIK', 'HINDU', 'BUDHA', 'KONGHUCU', 'TIDAK DIKETAHUI']
            let religionDatas = religionLabels.map(function(key) { return religionData['agama'][key]['total']; })

            let islamPercentage = Math.round((religionData['agama']['ISLAM']['total'] / religionData['total'] * 100));            
            islamPercentage === 0 ? document.getElementsByClassName('pdd__stat-religion-islam')[0].style.display = 'none' :
            document.getElementsByClassName('pdd__stat-religion-islam-percentage')[0].innerHTML = islamPercentage + '%';

            let kristenPercentage = Math.round((religionData['agama']['KRISTEN']['total'] / religionData['total'] * 100));   
            kristenPercentage === 0 ? document.getElementsByClassName('pdd__stat-religion-kristen')[0].style.display = 'none' :
            document.getElementsByClassName('pdd__stat-religion-kristen-percentage')[0].innerHTML =             
                Math.round((religionData['agama']['KRISTEN']['total'] / religionData['total'] * 100)) + '%';

            let katolikPercentage = Math.round((religionData['agama']['KATHOLIK']['total'] / religionData['total'] * 100));  
            katolikPercentage === 0 ? document.getElementsByClassName('pdd__stat-religion-katolik')[0].style.display = 'none' :          
            document.getElementsByClassName('pdd__stat-religion-katolik-percentage')[0].innerHTML = 
                Math.round((religionData['agama']['KATHOLIK']['total'] / religionData['total'] * 100)) + '%';

            let hinduPercentage = Math.round((religionData['agama']['HINDU']['total'] / religionData['total'] * 100));   
            hinduPercentage === 0 ? document.getElementsByClassName('pdd__stat-religion-hindu')[0].style.display = 'none' :         
            document.getElementsByClassName('pdd__stat-religion-hindu-percentage')[0].innerHTML = 
                Math.round((religionData['agama']['HINDU']['total'] / religionData['total'] * 100)) + '%';

            let buddhaPercentage = Math.round((religionData['agama']['BUDHA']['total'] / religionData['total'] * 100));   
            buddhaPercentage === 0 ? document.getElementsByClassName('pdd__stat-religion-buddha')[0].style.display = 'none' :         
            document.getElementsByClassName('pdd__stat-religion-buddha-percentage')[0].innerHTML = 
                Math.round((religionData['agama']['BUDHA']['total'] / religionData['total'] * 100)) + '%';

            let konghucuPercentage = Math.round((religionData['agama']['KONGHUCU']['total'] / religionData['total'] * 100)); 
            konghucuPercentage === 0 ? document.getElementsByClassName('pdd__stat-religion-konghucu')[0].style.display = 'none' :           
            document.getElementsByClassName('pdd__stat-religion-konghucu-percentage')[0].innerHTML = 
                Math.round((religionData['agama']['KONGHUCU']['total'] / religionData['total'] * 100)) + '%';

            var religionChartData = {   
                labels: religionLabels,                 
                datasets: [{     
                    data: religionDatas,
                    backgroundColor: ['rgb(245, 98, 133)', 'rgb(245, 162, 70)', 'rgb(246, 209, 89)', 'rgb(96, 190, 190)', 'rgb(91, 155, 234)', 'grey']
                }]
            };		

            var religionCtx = document.getElementById('pdd__stat-religion-graph').getContext('2d');
            new Chart(religionCtx, {
                type: 'doughnut',
                data: religionChartData,
                options: {
                    title: { display: false, text: 'Chart Agama' },
                    legend: { display: false },
                    tooltips: { mode: 'index', intersect: false },                        
                    plugins: {
                        datalabels: {
                            display: true,
                            color: 'white'                                
                        }
                    }
                }
            });  
        });            

        function transformDataAge(raw) {
            let ageGroup = { 
                "0-15": { "min": 0, "max": 15 },
                "15-25": { "min": 15, "max": 25 },
                "25-35": { "min": 25, "max": 35 },
                "35-45": { "min": 35, "max": 45 },
                "45-55": { "min": 45, "max": 55 },
                "55-65": { "min": 55, "max": 65 },
                ">65": { "min": 65, "max": 200 },
                "15-65": { "min": 15, "max": 65}
            };

            let all = {
                'ageGroup': {},
                'total': 0
            };

            for(let i = 0; i < raw.length; i++) {
                let r = raw[i];
                let val = parseInt(r.jumlah);

                let groups = Object.keys(ageGroup);                
                for(let j = 0; j < groups.length; j++) {
                    let groupKey = groups[j];
                    group = ageGroup[groupKey];
                    
                    if(r.min_umur >= group['min'] && r.max_umur <= group['max']) {
                        let jenisKelamin = r.jenis_kelamin.replace(/\s/g,'').toLowerCase();
                        jenisKelamin = jenisKelamin.charAt(0).toUpperCase() + jenisKelamin.slice(1);

                        if(!all['ageGroup'][groupKey]) { all['ageGroup'][groupKey] = { 'total': 0 }; }   
                        if(!all['ageGroup'][groupKey][jenisKelamin]) { all['ageGroup'][groupKey][jenisKelamin] = 0; }                     

                        all['ageGroup'][groupKey][jenisKelamin] += val;
                        all['ageGroup'][groupKey]['total'] += val;                        
                    }                    
                }

                all['total'] += val;
            }

            return all;
        }

        function transformLabeledData(raw, label, groupOther){
            let all = { 'total': 0 };
            all[label] = {};            
            let maxYear = 2017;

            for(let i = 0; i < raw.length; i++) {
                if (parseInt(raw[i].tahun) > maxYear) { maxYear = parseInt(raw[i].tahun); }
            }

            for(let i = 0; i < raw.length; i++){
                if (raw[i].tahun !== maxYear.toString()) { continue; }

                let r = raw[i];                
                let val = parseInt(r.jumlah);                

                let p = r[label].toUpperCase();
                p = normalizeLabel(label, p);
                
                let jenisKelamin = r.jenis_kelamin.replace(/\s/g,'').toLowerCase();
                jenisKelamin = jenisKelamin.charAt(0).toUpperCase() + jenisKelamin.slice(1);

                if(!all[label][p]) { all[label][p] = { 'Laki-laki': 0, 'Perempuan': 0, 'total': 0 }; }
                if(!all[label][p][jenisKelamin]) { all[label][p][jenisKelamin] = 0; }                
                all[label][p][jenisKelamin] += val;
                all[label][p]['total'] += val;
                all['total'] += val;
            }
            
            //remove values lesser than 2% of total and add to lain-lain
            if (groupOther) {
                let labels = Object.keys(all[label]);                        
                for(let i = 0; i < labels.length; i++) {
                    let l = labels[i];
                    if((all[label][l]['total'] / all['total']) > 0.02) { continue; }

                    let content = Object.keys(all[label][l]);
                    let other = label === 'pekerjaan' ? 'LAIN-LAIN' : 'TIDAK DIKETAHUI';

                    if (!(other in all[label])) { all[label][other] = { 'Laki-laki': 0, 'Perempuan': 0, 'total': 0 }; }

                    for(let j = 0; j < content.length; j++) {
                        all[label][other][content[j]] += all[label][l][content[j]];                    
                    }

                    delete all[label][l];
                }
            }
            
            return all;
        }       
        
        function findPendidikanGroup(label){
            let groups = Object.keys(pendidikanGroups);
            for(let i = 0; i < groups.length; i++) {
                let sidekaValues = pendidikanGroups[groups[i]].map(function(group) { return group.toUpperCase(); });
                if(sidekaValues.includes(label)) { return groups[i].toUpperCase(); }
            }
            return "TIDAK DIKETAHUI";
        }

        function normalizeLabel(type, label) {
            // Pekerjaan 
            if (type === 'pekerjaan') {
                if (label === 'BELUM KERJA' || label === 'BELUM/ TIDAK KERJA' || label === 'BELUM/TIDAK KERJA' || label === 'TIDAK KERJA') { return 'BELUM/TIDAK BEKERJA'; }
                if (label === 'PELAJAR' || label === 'PELAJAR / MAHASISWA') { return 'PELAJAR/MAHASISWA'; }
                if (label === 'WIRASWATA') { return 'WIRASWASTA'; }
                if (label === 'IBU RUMAH TANGGA') { return 'MENGURUS RUMAH TANGGA'; }
                if (label === '') { return 'LAIN-LAIN'; }
            }
            
            // Pendidikan
            if (type === 'pendidikan') {
                return findPendidikanGroup(label);
            }
            
            if (label === '') { return 'TIDAK DIKETAHUI'; }
            return label;
        }        
    </script>
<?php } else { ?>

<p>Data kependudukan belum diunggah</p>

<?php } ?>
