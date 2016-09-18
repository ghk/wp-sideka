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
        <a href="#">Rencana Jangka Menengah (RPJMDes 2016-2020)</a>
    </li>
    <li>
        <a href="#">Anggaran Tahunan (APBDes 2016)</a>
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
        white-space: pre;
    }

    .node-value {
        color: rgba(0,0,0,0.8);
        font-size: 9px;
        margin-top: 1px;
    }

</style>
<svg width="1030" height="500"></svg>
<div id="treemap" style="height: 500px; width: 1030px; position: relative;">
</div>
<script src="//d3js.org/d3.v4.min.js"></script>
<script>

    var format = d3.format(",d");

    var color = d3.scaleOrdinal()
        .range(d3.schemeCategory10
            .map(function(c) { c = d3.rgb(c); c.opacity = 0.6; return c; }));

    var stratify = d3.stratify()
        .parentId(function(d) { return d.id.substring(0, d.id.lastIndexOf(".")); });

    var treemap = d3.treemap()
        .size([1030, 500])
        .padding(1)
        .round(true);

    d3.csv("/wp-content/plugins/sideka/page/flare.csv", typeTreemap, function(error, data) {
        if (error) throw error;

        var root = stratify(data)
            .sum(function(d) { return d.value; })
            .sort(function(a, b) { return b.height - a.height || b.value - a.value; });

        treemap(root);

        d3.select("#treemap")
            .selectAll(".node")
            .data(root.leaves())
            .enter().append("div")
            .attr("class", "node")
            .attr("title", function(d) { return d.id + "\n" + format(d.value); })
            .style("left", function(d) { return d.x0 + "px"; })
            .style("top", function(d) { return d.y0 + "px"; })
            .style("width", function(d) { return d.x1 - d.x0 + "px"; })
            .style("height", function(d) { return d.y1 - d.y0 + "px"; })
            .style("background", function(d) { while (d.depth > 1) d = d.parent; return color(d.id); })
            .append("div")
            .attr("class", "node-label")
            .text(function(d) { return d.id.substring(d.id.lastIndexOf(".") + 1).split(/(?=[A-Z][^A-Z])/g).join("\n"); })
            .append("div")
            .attr("class", "node-value")
            .text(function(d) { return format(d.value); });
    });

    function typeTreemap(d) {
        d.value = +d.value;
        return d;
    }

    var svg = d3.select("svg"),
        margin = {top: 20, right: 20, bottom: 30, left: 50},
        width = svg.attr("width") - margin.left - margin.right,
        height = svg.attr("height") - margin.top - margin.bottom;

    var parseDate = d3.timeParse("%Y %b %d");

    var x = d3.scaleTime().range([0, width]),
        y = d3.scaleLinear().range([height, 0]),
        z = d3.scaleOrdinal(d3.schemeCategory10);

    var stack = d3.stack();

    var area = d3.area()
        .x(function(d, i) { return x(d.data.date); })
        .y0(function(d) { return y(d[0]); })
        .y1(function(d) { return y(d[1]); });

    var g = svg.append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    d3.tsv("/wp-content/plugins/sideka/page/data.tsv", type, function(error, data) {
        if (error) throw error;

        var keys = data.columns.slice(1);

        x.domain(d3.extent(data, function(d) { return d.date; }));
        z.domain(keys);
        stack.keys(keys);

        var layer = g.selectAll(".layer")
            .data(stack(data))
            .enter().append("g")
            .attr("class", "layer");

        layer.append("path")
            .attr("class", "area")
            .style("fill", function(d) { return z(d.key); })
            .attr("d", area);

        layer.filter(function(d) { return d[d.length - 1][1] - d[d.length - 1][0] > 0.01; })
            .append("text")
            .attr("x", width - 6)
            .attr("y", function(d) { return y((d[d.length - 1][0] + d[d.length - 1][1]) / 2); })
            .attr("dy", ".35em")
            .style("font", "10px sans-serif")
            .style("text-anchor", "end")
            .text(function(d) { return d.key; });

        g.append("g")
            .attr("class", "axis axis--x")
            .attr("transform", "translate(0," + height + ")")
            .call(d3.axisBottom(x));

        g.append("g")
            .attr("class", "axis axis--y")
            .call(d3.axisLeft(y).ticks(10, "%"));
    });

    function type(d, i, columns) {
        d.date = parseDate(d.date);
        for (var i = 1, n = columns.length; i < n; ++i) d[columns[i]] = d[columns[i]] / 100;
        return d;
    }

</script>

