<?php
/**
 * Created by PhpStorm.
 * User: F
 * Date: 9/23/2016
 * Time: 3:51 AM
 */

$ckan_host = "http://ckan.neon.microvac:5000";
$desa_id = "dermaji";
$package_id = $desa_id."-kependudukan";
$json = file_get_contents($ckan_host . '/api/3/action/package_show?id=' . $package_id);
$response = json_decode($json);
$resources = $response->result->resources;
?>
<style>
    #count-summary {
        width: 100%;
        text-align: center;
        margin: 40px 0 0 0;
    }
    #count-summary dt {
        text-transform: uppercase;
        margin-top: 15px;
        list-style-type: none;
        margin-left: 0;
    }
    #count-summary dd {
        list-style-type: none;
        font-size: 20px;
        margin-left: 0;
    }
</style>

<div class="clearfix">
    <div class="mh-content" style="float: left; margin-right: 2.5%">
        <h4 class="mh-widget-title">
            <span class="mh-widget-title-inner"><a href="#" class="mh-widget-title-link">Pekerjaan Penduduk</a></span>
        </h4>
        <div id="pekerjaan">
            <svg style="height: 250px;"></svg>
        </div>
    </div>
    <div class="mh-widget-col-1 mh-sidebar">
        <dl id="count-summary">
            <dt class="required">Penduduk Perempuan</dt>
            <dd id="count-female"></dd>
            <dt class="required">Penduduk Laki-laki</dt>
            <dd id="count-male"></dd>
            <dt class="required">Tidak Diketahui</dt>
            <dd id="count-unknown"></dd>
        </dl>
    </div>
</div>
<div class="clearfix">
    <div class="mh-content" style="float: left; margin-right: 2.5%">
        <h4 class="mh-widget-title">
            <span class="mh-widget-title-inner"><a href="#" class="mh-widget-title-link">Tingkat Pendidikan Penduduk</a></span>
        </h4>
        <div id="pendidikan">
            <svg style="height: 250px;"></svg>
        </div>
        <h4 class="mh-widget-title">
            <span class="mh-widget-title-inner"><a href="#" class="mh-widget-title-link">Kelompok Umur</a></span>
        </h4>
        <div id="umur">
            <svg style="height: 350px;"></svg>
        </div>
    </div>
    <div class="mh-widget-col-1 mh-sidebar">
        <h4 class="mh-widget-title">
            <span class="mh-widget-title-inner"><a href="#" class="mh-widget-title-link">Agama</a></span>
        </h4>
        <div id="agama">
            <svg style="height: 300px;"></svg>
        </div>
        <h4 class="mh-widget-title">
            <span class="mh-widget-title-inner"><a href="#" class="mh-widget-title-link">Status Kawin</a></span>
        </h4>
        <div id="statusKawin">
            <svg style="height: 300px;"></svg>
        </div>
    </div>
</div>


<link href="http://nvd3.org/assets/css/nv.d3.css" rel="stylesheet">
<script src="http://nvd3.org/assets/lib/d3.v3.js"></script>
<script src="http://nvd3.org/assets/js/nv.d3.js"></script>

<script type="text/javascript">
    document.getElementsByClassName("entry-header")[0].remove();
    var package_id = "<?= $package_id ?>";
    var ckan_host = "<?= $ckan_host ?>";
    var package = <?= $json ?>;
    var pekerjaan = package.result.resources.filter(function(r) {return r.name === "Pekerjaan Berdasarkan Jenis Kelamin"})[0];

    function summaryCount(data){
        var total = {"Perempuan": 0, "Laki - laki": 0, "Tidak Diketahui": 0};
        for(var i = 0; i < data.length; i++){
            var r = data[i];
            var val = parseInt(r.jumlah);
            var s = r.jenis_kelamin;
            total[s] += val;
        }
        document.getElementById("count-male").innerHTML = total["Laki - laki"];
        document.getElementById("count-female").innerHTML = total["Perempuan"];
        document.getElementById("count-unknown").innerHTML = total["Tidak Diketahui"];
    }

    d3.csv(pekerjaan.url, function(error, data) {
        summaryCount(data);

        var chart = nv.models.multiBarHorizontalChart()
            .x(function(d) { return d.label })
            .y(function(d) { return d.value })
            .margin({top: 30, right: 20, bottom: 50, left: 175})
            .tooltips(true)             //Show tooltips on hover.
            .transitionDuration(350)
            .stacked(true)
            .showControls(false);        //Allow user to switch between "Grouped" and "Stacked" mode.

        chart.yAxis
            .tickFormat(d3.format('d'));

        var transformed = transformDataStacked(data, "pekerjaan");
        d3.select('#pekerjaan svg')
            .datum(transformed)
            .call(chart);

        nv.utils.windowResize(chart.update);
    });

    var pendidikan = package.result.resources.filter(function(r) {return r.name === "Pendidikan Berdasarkan Jenis Kelamin"})[0];
    d3.csv(pendidikan.url, function(error, data) {
        var chart = nv.models.multiBarHorizontalChart()
            .x(function(d) { return d.label })
            .y(function(d) { return d.value })
            .margin({top: 30, right: 20, bottom: 50, left: 175})
            .tooltips(true)             //Show tooltips on hover.
            .transitionDuration(350)
            .stacked(true)
            .showControls(false);        //Allow user to switch between "Grouped" and "Stacked" mode.

        chart.yAxis
            .tickFormat(d3.format('d'));

        var transformed = transformDataStacked(data, "pendidikan");
        d3.select('#pendidikan svg')
            .datum(transformed)
            .call(chart);

        nv.utils.windowResize(chart.update);
    });

    var umur = package.result.resources.filter(function(r) {return r.name === "Kelompok Umur Berdasarkan Jenis Kelamin"})[0];
    d3.csv(umur.url, function(error, data) {
        var chart = nv.models.multiBarHorizontalChart()
            .x(function(d) { return d.label })
            .y(function(d) { return d.value })
            .margin({top: 30, right: 20, bottom: 50, left: 100})
            .tooltips(true)             //Show tooltips on hover.
            .transitionDuration(350)
            .stacked(true)
            .showControls(false);        //Allow user to switch between "Grouped" and "Stacked" mode.

        chart.yAxis
            .tickFormat(d3.format('d'));

        var transformed = transformDataPyramid(data);
        d3.select('#umur svg')
            .datum(transformed)
            .call(chart);

        nv.utils.windowResize(chart.update);
    });

    var agama = package.result.resources.filter(function(r) {return r.name === "Agama Berdasarkan Jenis Kelamin"})[0];
    d3.csv(agama.url, function(error, data) {
        var chart = nv.models.pieChart()
            .x(function(d) { return d.label })
            .y(function(d) { return d.value })
            .showLabels(false);

        d3.select("#agama svg")
            .datum(transformData(data, "agama"))
            .transition().duration(350)
            .call(chart);

        return chart;
    });

    var statusKawin = package.result.resources.filter(function(r) {return r.name === "Status Kawin Berdasarkan Jenis Kelamin"})[0];
    d3.csv(statusKawin.url, function(error, data) {
        var chart = nv.models.pieChart()
            .x(function(d) { return d.label })
            .y(function(d) { return d.value })
            .showLabels(false);

        d3.select("#statusKawin svg")
            .datum(transformData(data, "status_kawin"))
            .transition().duration(350)
            .call(chart);

        return chart;
    });

    function transformDataStacked(raw, label){
        //create aggregate dict
        var all = {};
        var allPerSex = {}
        var total = 0;
        for(var i = 0; i < raw.length; i++){
            var r = raw[i];
            var val = parseInt(r.jumlah);
            var p = r[label].toUpperCase();
            if(!all[p])
            {
                all[p] = 0;
            }
            all[p] += val;
            if(!allPerSex[p])
            {
                allPerSex[p] ={};
            }
            allPerSex[p][r.jenis_kelamin] = val;
            total += val;
        }

        //remove values lesser than 2% of total
        var min = Math.round(0.01 * total);
        var keys = Object.keys(all);
        var filteredKeys = [];
        var etcS = {"Perempuan": 0, "Laki - laki": 0, "Tidak Diketahui": 0};
        var etc = 0;
        for(var i = 0; i < keys.length; i++) {
            var key = keys[i];
            if(all[key] < min){
                var sexes = Object.keys(etcS);
                for(var j = 0; j < sexes.length; j++)
                {
                    var sex = sexes[j];
                    if(allPerSex[key][sex]) {
                        etcS[sex] += allPerSex[key][sex];
                    }
                }
                etc += all[key];
            } else {
                filteredKeys.push(key);
            }
        }
        console.log(etc);
        if(etc > 0) {
            var etcN = "LAIN - LAIN";
            all[etcN] = etc;
            allPerSex[etcN] = etcS;
            filteredKeys.push(etcN);
        }

        var sortedPekerjaan = filteredKeys.sort(function(a, b){
                var va = all[a];
                var vb = all[b];
                return vb - va;
        });

        console.log(allPerSex);
        console.log(sortedPekerjaan);

        return ["Perempuan", "Laki - laki", "Tidak Diketahui"].map(function(sex){
            return {
                key: sex,
                values: sortedPekerjaan
                    .map(function(p){
                        var val = allPerSex[p][sex];
                        if(!val)
                            val == 0;
                        return {"label": p, "value": val}
                    })
            }
        });
    }

    function transformDataPyramid(raw){
        //create aggregate dict
        var all = {};
        var allPerSex = {}
        var age = {}
        var total = 0;
        for(var i = 0; i < raw.length; i++){
            var r = raw[i];
            var val = parseInt(r.jumlah);
            var p = r.min_age + " - " + r.max_age;
            age[p] = r.min_age;
            if(!all[p])
            {
                all[p] = 0;
            }
            all[p] += val;
            if(!allPerSex[p])
            {
                allPerSex[p] ={};
            }
            allPerSex[p][r.jenis_kelamin] = val;
            total += val;
        }

        var sorted = Object.keys(all).sort(function(a, b){
            return age[b] - age[a];
        });

        return ["Perempuan", "Laki - laki", "Tidak Diketahui"].map(function(sex){
            return {
                key: sex,
                values: sorted
                    .map(function(p){
                        var val = allPerSex[p][sex];
                        if(sex == "Perempuan")
                            val = -val;
                        if(!val)
                            val == 0;
                        return {"label": p, "value": val}
                    })
            }
        });
    }

    function transformData(raw, label){
        var all = {};
        for(var i = 0; i < raw.length; i++){
            var r = raw[i];
            var val = parseInt(r.jumlah);
            var p = r[label].toUpperCase();
            if(!all[p])
            {
                all[p] = 0;
            }
            all[p] += val;
        }

        var sorted = Object.keys(all).sort(function(a, b){
            var va = all[a];
            var vb = all[b];
            return vb - va;
        });

        return sorted.map(function(p){
            var val = all[p];
            if(!val)
                val == 0;
            return {"label": p, "value": val}
        });
    }
</script>

