<style>
    .entry-content ul.entry-tabs {margin-left: 0px; list-style: none;}
    .entry-tabs { font-size: 12px; font-size: 0.75rem; color: #fff; line-height: 1; margin-bottom: 20px; margin-bottom: 1.25rem; }
    .entry-tabs::after { display: block; content: '.'; height: 2px; background-color: #e64946; clear: both; margin-top: -5px;}
    .entry-tabs li { float: left; font-weight: 700; margin: 0 6px 6px 0; margin: 0 0.375rem 0.375rem 0; background: #2a2a2a; text-transform: uppercase; }
    .entry-tabs li:hover, .entry-tabs li.active { background: #e64946; }
    .entry-tabs a, .entry-tabs a:hover, .entry-tabs li.active a { display: block; color: #fff; padding: 10px 15px; }
    .entry-tabs .fa { float: left; padding: 10px; margin-right: 6px; background: #e64946; }
</style>
<ul class="entry-tabs">
    <li class="active">
        <a href="#">Anggaran Tahunan (APBDes 2016)</a>
    </li>
    <li>
        <a href="#">Rencana Jangka Menengah (RPJMDes 2016-2020)</a>
    </li>
</ul>

<style>

    .node {
        box-sizing: border-box;
        position: absolute;
        overflow: hidden;
    }

    .node-label {
        padding: 4px;
        line-height: 1em;
    }

    .node-value {
        color: rgba(0,0,0,0.8);
        font-size: 9px;
        margin-top: 1px;
    }

</style>
<h3>Pendapatan Desa</h3>
<div id="treemapi" style="height: 200px; width: 1030px; position: relative;">
</div>
<h3>Belanja Desa</h3>
<div id="treemaps" style="height: 700px; width: 1030px; position: relative;">
</div>
<script src="//d3js.org/d3.v4.min.js"></script>
<script>

    var format = d3.format(",d");

    var color = d3.scaleOrdinal()
        .range(d3.schemeCategory10
            .map(function(c) { c = d3.rgb(c); c.opacity = 0.6; return c; }));

    var stratify = d3.stratify()
        .parentId(function(d) {
            return d.id.substring(0, d.id.lastIndexOf("."));
        });

    var treemaps = d3.treemap()
        .size([1030, 700])
        .padding(1)
        .round(true);
    var treemapi = d3.treemap()
        .size([1030, 200])
        .padding(1)
        .round(true);

    d3.tsv("/wp-content/plugins/sideka/page/apbdes.tsv", typeTreemap, function(error, data) {
        if (error) throw error;

        var income = data.filter(i => i.id.split("\.").length < 4 && i.id.startsWith('1'))
        var iroot = stratify(income)
            .each(function(d) { d.value = d.data.value; })
            .sort(function(a, b) { return b.height - a.height || b.value - a.value; });

        treemapi(iroot);

        d3.select("#treemapi")
            .selectAll(".node")
            .data(iroot.leaves())
            .enter().append("div")
            .attr("class", "node")
            .attr("title", function(d) { return d.id + " " + d.data.name + "\n" + format(d.value); })
            .style("left", function(d) { return d.x0 + "px"; })
            .style("top", function(d) { return d.y0 + "px"; })
            .style("width", function(d) { return d.x1 - d.x0 + "px"; })
            .style("height", function(d) { return d.y1 - d.y0 + "px"; })
            .style("background", function(d) { while (d.depth > 1) d = d.parent; return color(d.id); })
            .append("div")
            .attr("class", "node-label")
            .text(function(d) { return d.data.name; })
            .append("div")
            .attr("class", "node-value")
            .text(function(d) { return format(d.value); });

        var spending = data.filter(i => i.id.split("\.").length < 5 && i.id.startsWith('2') && i.name != 'Belanja Barang dan Jasa' && i.name != 'Belanja Modal');
        var sroot = stratify(spending)
            .each(function(d) { d.value = d.data.value; })
            .sort(function(a, b) { return b.height - a.height || b.value - a.value; });

        treemaps(sroot);

        d3.select("#treemaps")
            .selectAll(".node")
            .data(sroot.leaves())
            .enter().append("div")
            .attr("class", "node")
            .attr("title", function(d) { return d.id + " " + d.data.name + "\n" + format(d.value); })
            .style("left", function(d) { return d.x0 + "px"; })
            .style("top", function(d) { return d.y0 + "px"; })
            .style("width", function(d) { return d.x1 - d.x0 + "px"; })
            .style("height", function(d) { return d.y1 - d.y0 + "px"; })
            .style("background", function(d) { while (d.depth > 1) d = d.parent; return color(d.id); })
            .append("div")
            .attr("class", "node-label")
            .text(function(d) { return d.data.name; })
            .append("div")
            .attr("class", "node-value")
            .text(function(d) { return format(d.value); });
    });

    function typeTreemap(d) {
        d.value = +d.value;
        return d;
    }


</script>

