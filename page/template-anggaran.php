<?php
/**
 * Created by PhpStorm.
 * User: F
 * Date: 9/23/2016
 * Time: 3:51 AM
 */

$desa_id = "mandalamekar";
$server_name = $_SERVER["SERVER_NAME"];
$server_splits = explode(".", $server_name);
if($server_splits[0].".desa.id" == $server_name || $server_splits[0].".sideka.id" == $server_name){
    $desa_id = $server_splits[0];
}

$ckan_host = "http://data.prakarsadesa.id";
#$ckan_host = "http://ckan.neon.microvac:5000";
$package_id = $desa_id."-keuangan";
$json = @file_get_contents($ckan_host . '/api/3/action/package_show?id=' . $package_id);
$package_exists = json_decode($json)->success;
$desa_code = sideka_get_desa_code();
?>
<?php if($desa_code) { 
$years_json = @file_get_contents("http://api.keuangan.sideka.id/regions/".$desa_code."/years");
$year = max(json_decode($years_json));

$progress_recapitulations_json = @file_get_contents("http://api.keuangan.sideka.id/progress/recapitulations/year/".$year."?sort=region.name&is_lokpri&region_id=".$desa_code);
$progress_recapitulations = json_decode($progress_recapitulations_json);
$progress_recapitulation = null;
foreach($progress_recapitulations as $cur){
    if($cur->fk_region_id == $desa_code){
	$progress_recapitulation = $cur;
        break;
    }
}
?>
<?php if($progress_recapitulation) { 
$all_spending_recapitulations_json = @file_get_contents("http://api.keuangan.sideka.id/budget/recapitulations/year/".$year."?sort=region.name&is_lokpri&region_id=".$desa_code);
$all_spending_recapitulations = json_decode($all_spending_recapitulations_json);
$spending_recapitulations = array();
foreach($all_spending_recapitulations as $cur){
    if($cur->fk_region_id == $desa_code){
	$spending_recapitulations[] = $cur;
    }
}
$progress_timelines_json = @file_get_contents("http://api.keuangan.sideka.id/progress/timelines/region/".$desa_code."/year/".$year."?sort=region.name&is_lokpri");
?>
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
            font-size: 16px;
            margin-left: 0;
        }
        #count-summary.larger dd {
            font-size: 30px;
        }
    </style>

    <style>
        #details {
            background: #fff;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
        .title {
            font-weight: bold;
            font-size: 24px;
            text-align: center;
            margin-top: 6px;
            margin-bottom: 6px;
        }
        text {
            pointer-events: none;
        }
        #count-summary.larger {
            margin-top: 40px;
        }
        .grandparent text {
            font-weight: bold;
        }
        rect {
            fill: none;
            stroke: #fff;
        }
        rect.parent,
        .grandparent rect {
            stroke-width: 2px;
        }
        rect.parent {
            pointer-events: none;
        }
        .grandparent rect {
            fill: orange;
        }
        .grandparent:hover rect {
            fill: #ee9700;
        }
        .children rect.parent,
        .grandparent rect {
            cursor: pointer;
        }
        .children rect.parent {
            fill: #bbb;
            fill-opacity: .5;
        }
        .children:hover rect.child {
            fill: #bbb;
        }
        .mh-widget-title-link select {
            border: 0 none;
            font-weight: 600;
            color: #e64946;
            font-size: 16px;
            line-height: 1.3;
            font-family: 'Open Sans', Helvetica, Arial, sans-serif;
        }
        .bar-values {
            fill: #000;
            fill-opacity: 1;
            stroke: none;
        }
        .nv-x .nv-axis .tick text {
            font-size: 18px;
        }
    </style>

    <div class="clearfix" style="margin-bottom: 20px;">
        <h4 class="mh-widget-title">
            <span class="mh-widget-title-inner"><a class="mh-widget-title-link">APBDes Tahun Anggaran <span id="tahun-anggaran"></span></a></span>
        </h4>
        <div class="mh-widget-col-1 mh-sidebar">
            <dl id="count-summary" class="larger">
                <dt class="required">Anggaran Pendapatan</dt>
                <dd id="count-pendapatan"></dd>
            </dl>
        </div>
        <div class="mh-widget-col-1 mh-sidebar">
            <dl id="count-summary" class="larger">
            <dt class="required">Realisasi Pendapatan (Sep <?php echo $year; ?>)</dt>
            <dd id="count-realisasi-pendapatan"></dd>
            </dl>
        </div>
        <div class="mh-widget-col-1 mh-sidebar">
            <dl id="count-summary" class="larger">
            <dt class="required">Realisasi Belanja (Sep <?php echo $year; ?>)</dt>
            <dd id="count-realisasi-belanja"></dd>
        </div>
    </div>
    <div class="clearfix">
        <h4 class="mh-widget-title">
            <span class="mh-widget-title-inner"><a class="mh-widget-title-link">Pendapatan Desa</a></span>
        </h4>
        <div class="mh-widget-col-1 mh-sidebar">
		<div id="pendapatan-pie">
		    <svg style="height: 300px;"></svg>
		</div>
        </div>
        <div class="mh-content">
		<div id="pendapatan-timeline">
		    <svg style="height: 300px;"></svg>
		</div>
        </div>
    </div>
    <div class="clearfix">
        <h4 class="mh-widget-title">
            <span class="mh-widget-title-inner"><a class="mh-widget-title-link">Belanja Desa</a></span>
        </h4>
        <div class="mh-widget-col-1 mh-sidebar">
		<div id="belanja-pie">
		    <svg style="height: 300px;"></svg>
		</div>
        </div>
        <div class="mh-content">
		<div id="belanja-timeline">
		    <svg style="height: 300px;"></svg>
		</div>
        </div>
    </div>
    <div class="clearfix">
        <h4 class="mh-widget-title">
            <span class="mh-widget-title-inner"><a class="mh-widget-title-link">Rincian Belanja Desa Interaktif<select id="year-selector">
                    </select></a></span>
        </h4>
        <div id="details" style="width: 100%; height: 700px;">
        </div>
    </div>


    <link href="/wp-content/plugins/sideka/nv.d3.css" rel="stylesheet">
    <script src="/wp-content/plugins/sideka/d3.v3.js"></script>
    <script src="/wp-content/plugins/sideka/nv.d3.js"></script>

    <script type="text/javascript">
        var width = d3.select("#details").node().getBoundingClientRect().width;
        document.getElementsByClassName("entry-header")[0].remove();
        var desa_id = "<?= $desa_id ?>";
        var desa_code = "<?= $desa_code ?>";
		var progress_recapitulation = <?= json_encode($progress_recapitulation) ?>;
		var spending_recapitulations = <?= json_encode($spending_recapitulations) ?>;
		var progress_timelines = <?= $progress_timelines_json ?>;
        var package_id = "<?= $package_id ?>";
        var ckan_host = "<?= $ckan_host ?>";
        var package = <?= $json ?>;
        var years = [];
        var apbdesData = {};
        var apbdesSums = {};
        var f = d3.format(".3s");
        var format = function(d) {
            if(d === 0)
                return "Rp. 0";
            return "Rp. "+f(d).replace(new RegExp("\\.", "g"), ",");
        };
	onAllApbdesLoaded();
        var apbdeses = package.result.resources
            .filter(function(r) {return r.name.startsWith("APBDes ")});
        apbdeses.forEach(function(apbdes){
                d3.csv(ckan_host + apbdes.url, function(error, data) {
                    var year = apbdes.name.substring(7);
                    apbdesData[year] = data;
                    apbdesSums[year] = calculateSums(data);
                    showApbdes(year, apbdes, data);
                    years.push(year)
                    if(years.length == apbdeses.length){
            		setupApbdesSelect();
                    }
                });
        });
        function onAllApbdesLoaded(){
            //setupPieChart("#pendapatan-pie", "revenue");
	    setupTimelineChart("#pendapatan-timeline", {'transferred_bhpr': 'Bagi Hasil Pajak', 'transferred_add': 'ADD', 'transferred_dd': ' Dana Desa'});
            setupSpendingPieChart("#belanja-pie");
	    setupTimelineChart("#belanja-timeline", {'realized_spending': 'Realisasi Belanja'});
            setupCountSummary();
        }
        function setupCountSummary() {
            jQuery("#tahun-anggaran").html(<?php echo $year; ?>);
            var fmt = d3.format("0,000");
            var f = function(d){
                return "Rp. "+fmt(d).replace(new RegExp(",", "g"), ".");
            }
            function update(selector, value){
                jQuery(selector).html(f(value));
            }
            update("#count-pendapatan", progress_recapitulation['budgeted_revenue']);
            update("#count-realisasi-pendapatan", progress_recapitulation['transferred_revenue']);
            update("#count-realisasi-belanja", progress_recapitulation['realized_spending']);
        }
	function setupSpendingPieChart(selector){
            var chart = nv.models.pieChart()
                .x(function(d) { return d.type.name })
                .y(function(d) { return d.budgeted })
                .labelThreshold(.25)
                .showLabels(true);
            d3.select(selector+" svg")
                .datum(spending_recapitulations)
                .call(chart);
            return chart;
	}
	function setupTimelineChart(selector, properties){
		var marginTop = 30;
		if(width < 600){
			d3.select(selector+' svg')
			.style("height", "400px");
			marginTop = 130;
		}
		var months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
		var data = progress_timelines;
		data.sort(function(a,b){return a.month-b.month;});
		var groupedData = Object.keys(properties).map(function(key){
			return {
				key: properties[key],
				values: data.map(function(d){return {label: d['month'], value: d[key]};})
			}
		});
		console.log(groupedData);
		var chart = nv.models.lineChart()
			.x(function(d) { return d.label })
			.y(function(d) { return d.value })
			.margin({top: marginTop, right: 50, bottom: 50, left: 100})
			.useInteractiveGuideline(true)  //We want nice looking tooltips and a guideline!
			.showLegend(true)       //Show the legend, allowing users to turn on/off line series.
			.showYAxis(true)        //Show the y-axis
			.showXAxis(true)        //Show the x-axis
		;
		chart.yAxis
		.tickFormat(format);
		chart.xAxis
		.tickFormat(function(m){return months[m-1];});
		if(width > 600){
			chart.xAxis
			.tickValues(data.map(function(d){return d.month;}));
		}
		d3.select(selector+" svg")
		.datum(groupedData)
		.call(chart);
	}
        function setupHistoricalChart(id, selectors){
            var marginTop = 30;
            if(width < 600){
                d3.select(id+' svg')
                    .style("height", "400px");
                marginTop = 130;
            }
            var chart = nv.models.multiBarChart()
                    .x(function(d) { return d.label })
                    .y(function(d) { return d.value })
                    .margin({top: marginTop, right: 0, bottom: 50, left: 100})
                    //.transitionDuration(350)
                    .stacked(true)
                    .showControls(false)   //Allow user to switch between 'Grouped' and 'Stacked' mode.
                    .groupSpacing(0.1)    //Distance between each group of bars.
                ;
            //chart.bars.forceY([0]);
            chart.yAxis
                .tickFormat(format);
            var transformed = transformDataHistorical(selectors);
            console.log(selectors);
            console.log(transformed);
            d3.select(id+' svg')
                .datum(transformed)
                .call(chart);
            function doUpdate() {
                //oldUpdate.apply(this, arguments);
                var container = d3.select(id + ' svg');
                container.selectAll('.nv-multibar .nv-group').each(function(group){
                    var g = d3.select(this);
                    // Remove previous labels if there is any
                    g.selectAll('text').remove();
                    var width = container.node().getBoundingClientRect().width;
                    g.selectAll('.nv-bar').each(function(bar){
                        var b = d3.select(this);
                        var barWidth = b.attr('width');
                        var barHeight = b.attr('height');
                        if(barHeight < 14){
                            return;
                        }
                        g.append('text')
                            // Transforms shift the origin point then the x and y of the bar
                            // is altered by this transform. In order to align the labels
                            // we need to apply this transform to those.
                            .attr('transform', b.attr('transform'))
                            .text(function(d){
                                // Two decimals format
                                var height = this.getBBox().height;
                                if(width < 600)
                                    return format(parseFloat(bar.y));
                                var key = d.key;
                                if(key && key.startsWith("Bidang "))
                                    key = key.replace("Bidang ", "").replace(" Desa", "");
                                return key + " - " + format(parseFloat(bar.y));
                            })
                            .attr('y', function(){
                                // Center label vertically
                                var height = this.getBBox().height;
                                return parseFloat(b.attr('y')) + (parseFloat(barHeight) / 2) - (height / 2) + 12;
                            })
                            .attr('x', function(){
                                // Center label horizontally
                                var width = this.getBBox().width;
                                return parseFloat(b.attr('x')) + (parseFloat(barWidth) / 2) - (width / 2);
                            })
                            .attr('class', 'bar-values');
                    });
                });
            }
            chart.dispatch.on('renderEnd', doUpdate);
            //chart.update = doUpdate;
            //chart.update();
            nv.utils.windowResize(chart.update);
            function transformDataHistorical(selectors){
                return selectors.map(function(selector){
                    var item = apbdesData[years[0]].filter(selector)[0];
		    if(!item)
			console.log(selector);
		    if(item == undefined)item = [];
                    var code = item.kode_rekening;
                    return {
                        key: item.uraian,
                        values: years.slice(0).reverse().map(function(year){
                            var value = null;
                            var yearItem = apbdesData[year].filter(selector)[0];
                            var c = code;
                            if(yearItem) {
                                value = yearItem.anggaran;
                                c = yearItem.kode_rekening;
                            }
                            if(!Number.isFinite(value)) {
                                value = apbdesSums[year][c];
                            }
                            if(!Number.isFinite(value))
                                value = 0;
                            return {
                                label: year,
                                value: value,
                            }
                        })
                    }
                });
            }
        }
        function calculateSums(rows){
            function getValue(row, index, rows){
                var anggaran = parseInt(row.anggaran);
                if(Number.isFinite(anggaran)){
                    if(row.kode_rekening){
                        sums[row.kode_rekening] = anggaran;
                    }
                    return anggaran;
                }
                var sum = 0;
                var dotCount = row.kode_rekening.split(".").length;
                var i = index + 1;
                var allowDetail = true;
                while(i < rows.length){
                    var nextRow  = rows[i];
                    var nextDotCount = nextRow.kode_rekening ? nextRow.kode_rekening.split(".").length : 0;
                    if(!nextRow.kode_rekening && allowDetail){
                        var nextAnggaran = parseInt(nextRow.anggaran);
                        if(Number.isFinite(nextAnggaran)){
                            sum += nextAnggaran;
                        }
                    } else if(nextRow.kode_rekening && nextRow.kode_rekening.startsWith(row.kode_rekening) && (dotCount + 1 == nextDotCount)){
                        allowDetail = false;
                        sum += getValue(nextRow, i, rows);
                    } else if(nextRow.kode_rekening && !nextRow.kode_rekening.startsWith(row.kode_rekening) ){
                        break;
                    }
                    i++;
                }
                sums[row.kode_rekening] = sum;
                return sum;
            }
            var sums = {};
            for(var i = 0; i < rows.length; i++){
                var row = rows[i];
                if(row.kode_rekening && !sums[row.kode_rekening]){
                    getValue(row, i, rows);
                }
            }
            return sums;
        }
        function setupApbdesSelect(){
            var $ = window.jQuery;
            years.sort().reverse();
            $("#year-selector").change(function(){
                var val = $(this).val();
                $("#details > div").each(function(){$(this).hide();});
                $("#details > div[data-year='"+val+"']").show();
            });
            years.forEach(function(year){
                $("#year-selector").append("<option>"+year+"</option>");
            });
            $("#details > div[data-year='"+years[0]+"']").show();
        }
        function showApbdes(year, apbdes, data){
            window.data = data;
            var belanja = data.filter(function(r){return r.kode_rekening && r.kode_rekening.startsWith("2.");});
            var root = { key: "2", name: "Belanja", values: [] };
            belanja.forEach(function(b){
                var splits = b.kode_rekening.split(".");
                var parentCodes = [];
                if(splits.length >= 3){
                    for(var i = 1; i < splits.length; i++){
                        parentCodes.push(splits.slice(0, i).join("."));
                    }
                }
                var parent = root;
                var found = null;
                for(var i = 0; i < parentCodes.length; i++) {
                    var parentCode = parentCodes[i];
                    if (parent.key == parentCode) {
                        found = parent
                    } else {
                        found = parent.values.filter(function (v) {
                            return v.key == parentCode
                        })[0];
                        if (!found) {
                            console.log("cannot find ", parentCode);
                            return;
                        }
                    }
                    parent = found;
                }
                var val = parseInt(b.anggaran);
                if(!isFinite(val))
                    val = 0;
                parent.values.push({
                    key: b.kode_rekening,
                    name: b.uraian,
                    value: val,
                    values: [],
                });
            });
            var cleanUp = function(node){
                //node.key = node.key + " " + node.name;
                node.key = node.name;
                if(node.values.length){
                    delete node.value;
                    for(var i = 0; i < node.values.length; i++){
                        cleanUp(node.values[i]);
                    }
                } else {
                    delete node.values;
                }
                delete node.name;
            }
            cleanUp(root);
            main({year: year}, root);
        }
        var defaults = {
            margin: {top: 24, right: 0, bottom: 0, left: 0},
            rootname: "TOP",
            format: ",d",
            title: "",
            width: width,
            height: 700
        };
        //http://bl.ocks.org/ganeshv/6a8e9ada3ab7f2d88022
        function main(o, data) {
            var $ = jQuery;
            var $div = $("<div></div>").appendTo($("#details")).attr("data-year", o.year);
            var root,
                opts = $.extend(true, {}, defaults, o),
                formatNumber = format,
                rname = opts.rootname,
                margin = opts.margin,
                theight = 36 + 16;
            $div.width(opts.width).height(opts.height);
            var width = opts.width - margin.left - margin.right,
                height = opts.height - margin.top - margin.bottom - theight,
                transitioning;
            var color = d3.scale.category20c();
            var x = d3.scale.linear()
                .domain([0, width])
                .range([0, width]);
            var y = d3.scale.linear()
                .domain([0, height])
                .range([0, height]);
            var treemap = d3.layout.treemap()
                .children(function(d, depth) { return depth ? null : d._children; })
                .sort(function(a, b) { return a.value - b.value; })
                .ratio(height / width * 0.5 * (1 + Math.sqrt(5)))
                .round(false);
            var svg = d3.select($div[0]).append("svg")
                .attr("width", width + margin.left + margin.right)
                .attr("height", height + margin.bottom + margin.top)
                .style("margin-left", -margin.left + "px")
                .style("margin.right", -margin.right + "px")
                .append("g")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")")
                .style("shape-rendering", "crispEdges");
            var grandparent = svg.append("g")
                .attr("class", "grandparent");
            grandparent.append("rect")
                .attr("y", -margin.top)
                .attr("width", width)
                .attr("height", margin.top);
            grandparent.append("text")
                .attr("x", 6)
                .attr("y", 6 - margin.top)
                .attr("dy", ".75em");
            if (opts.title) {
                $div.prepend("<p class='title'>" + opts.title + "</p>");
            }
            if (data instanceof Array) {
                root = { key: rname, values: data };
            } else {
                root = data;
            }
            initialize(root);
            accumulate(root);
            trim(root);
            layout(root);
            display(root);
            $div.hide();
            if (window.parent !== window) {
                var myheight = document.documentElement.scrollHeight || document.body.scrollHeight;
                window.parent.postMessage({height: myheight}, '*');
            }
            function initialize(root) {
                root.x = root.y = 0;
                root.dx = width;
                root.dy = height;
                root.depth = 0;
            }
            // Aggregate the values for internal nodes. This is normally done by the
            // treemap layout, but not here because of our custom implementation.
            // We also take a snapshot of the original children (_children) to avoid
            // the children being overwritten when when layout is computed.
            function accumulate(d) {
                return (d._children = d.values)
                    ? d.value = d.values.reduce(function(p, v) { return p + accumulate(v); }, 0)
                    : d.value;
            }
            function trim(d){
                var arr = d.values;
                for(var i = arr.length - 1; i >= 0; i--){
                    var c = arr[i];
                    if(c.value == 0){
                        arr.splice(i, 1);
                    } else {
                        if(c.values)
                            trim(c);
                    }
                }
            }
            // Compute the treemap layout recursively such that each group of siblings
            // uses the same size (1�1) rather than the dimensions of the parent cell.
            // This optimizes the layout for the current zoom state. Note that a wrapper
            // object is created for the parent node for each group of siblings so that
            // the parent�s dimensions are not discarded as we recurse. Since each group
            // of sibling was laid out in 1�1, we must rescale to fit using absolute
            // coordinates. This lets us use a viewport to zoom.
            function layout(d) {
                if (d._children) {
                    treemap.nodes({_children: d._children});
                    d._children.forEach(function(c) {
                        c.x = d.x + c.x * d.dx;
                        c.y = d.y + c.y * d.dy;
                        c.dx *= d.dx;
                        c.dy *= d.dy;
                        c.parent = d;
                        layout(c);
                    });
                }
            }
            function display(d) {
                grandparent
                    .datum(d.parent)
                    .on("click", transition)
                    .select("text")
                    .text(name(d));
                var g1 = svg.insert("g", ".grandparent")
                    .datum(d)
                    .attr("class", "depth");
                var g = g1.selectAll("g")
                    .data(d._children)
                    .enter().append("g");
                g.filter(function(d) { return d._children; })
                    .classed("children", true)
                    .on("click", transition);
                var children = g.selectAll(".child")
                    .data(function(d) { return d._children || [d]; })
                    .enter().append("g");
                children.append("rect")
                    .attr("class", "child")
                    .call(rect)
                    .append("title")
                    .text(function(d) { return d.key + " (" + formatNumber(d.value) + ")"; });
                children.append("text")
                    .attr("class", "ctext")
                    .text(function(d) { return d.key; })
                    .call(text2);
                g.append("rect")
                    .attr("class", "parent")
                    .call(rect);
                var t = g.append("text")
                    .attr("class", "ptext")
                    .attr("dy", ".75em")
                t.append("tspan")
                    .text(function(d) { return d.key; });
                t.append("tspan")
                    .attr("dy", "1.0em")
                    .text(function(d) { return formatNumber(d.value); });
                t.call(text);
                g.selectAll("rect")
                    .style("fill", function(d) { return color(d.key); });
                function transition(d) {
                    if (transitioning || !d) return;
                    transitioning = true;
                    var g2 = display(d),
                        t1 = g1.transition().duration(750),
                        t2 = g2.transition().duration(750);
                    // Update the domain only after entering new elements.
                    x.domain([d.x, d.x + d.dx]);
                    y.domain([d.y, d.y + d.dy]);
                    // Enable anti-aliasing during the transition.
                    svg.style("shape-rendering", null);
                    // Draw child nodes on top of parent nodes.
                    svg.selectAll(".depth").sort(function(a, b) { return a.depth - b.depth; });
                    // Fade-in entering text.
                    g2.selectAll("text").style("fill-opacity", 0);
                    // Transition to the new view.
                    t1.selectAll(".ptext").call(text).style("fill-opacity", 0);
                    t1.selectAll(".ctext").call(text2).style("fill-opacity", 0);
                    t2.selectAll(".ptext").call(text).style("fill-opacity", 1);
                    t2.selectAll(".ctext").call(text2).style("fill-opacity", 1);
                    t1.selectAll("rect").call(rect);
                    t2.selectAll("rect").call(rect);
                    // Remove the old node when the transition is finished.
                    t1.remove().each("end", function() {
                        svg.style("shape-rendering", "crispEdges");
                        transitioning = false;
                    });
                }
                return g;
            }
            function text(text) {
                text.selectAll("tspan")
                    .attr("x", function(d) { return x(d.x) + 6; })
                text.attr("x", function(d) { return x(d.x) + 6; })
                    .attr("y", function(d) { return y(d.y) + 6; })
                    .style("opacity", function(d) { return this.getComputedTextLength() < x(d.x + d.dx) - x(d.x) ? 1 : 0; });
            }
            function text2(text) {
                text.attr("x", function(d) { return x(d.x + d.dx) - this.getComputedTextLength() - 6; })
                    .attr("y", function(d) { return y(d.y + d.dy) - 6; })
                    .style("opacity", function(d) { return this.getComputedTextLength() < x(d.x + d.dx) - x(d.x) ? 1 : 0; });
            }
            function rect(rect) {
                rect.attr("x", function(d) { return x(d.x); })
                    .attr("y", function(d) { return y(d.y); })
                    .attr("width", function(d) { return x(d.x + d.dx) - x(d.x); })
                    .attr("height", function(d) { return y(d.y + d.dy) - y(d.y); });
            }
            function name(d) {
                return d.parent
                    ? name(d.parent) + " / " + d.key + " (" + formatNumber(d.value) + ")"
                    : d.key + " (" + formatNumber(d.value) + ")";
            }
        }
    </script>
<?php } else { ?>
    <p>Data Keuangan belum terunggah</p>

<?php } ?>
<?php } else { ?>

    <p>Kode Kemendagri desa belum diisi, silahkan hubungi Super Administrator Sideka untuk mengisi Kode Desa Anda</p>

<?php } ?>

