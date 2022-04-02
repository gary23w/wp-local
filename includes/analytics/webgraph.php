<?php
function ortho_line_graph($data) {
        // Some data
        $ydata = $data;             
        // Create the graph. These two calls are always required
        $graph = new Graph(350,250);
        $graph->SetScale('textlin');
            
        // Create the linear plot
        $lineplot=new LinePlot($ydata);
        $lineplot->SetColor('blue');
            
        // Add the plot to the graph
        $graph->Add($lineplot);
            
        // draw the graph
        $graph->Stroke(GARY_PLUGIN_URI . "includes/analytics/graphs/line.png");
}

function ortho_line_plot($ydata) {    
    // Some (random) data
    
    // Size of the overall graph
    $width=350;
    $height=250;
    
    // Create the graph and set a scale.
    // These two calls are always required
    $graph = new Graph($width,$height);
    $graph->SetScale('intlin');

    //get date 
    $date = date('Y-m-d');
    $date = strtotime($date);
    
    // Setup margin and titles
    $graph->SetMargin(40,20,20,40);
    $graph->title->Set('Line plot for 7 day.');
    $graph->subtitle->Set($date);
    $graph->xaxis->title->Set('Operator');
    $graph->yaxis->title->Set('# of requests');
    
    
    // Create the linear plot
    $lineplot=new LinePlot($ydata);
    
    // Add the plot to the graph
    $graph->Add($lineplot);
    
    // draw the graph
    $graph->Stroke(GARY_PLUGIN_URI . "includes/analytics/graphs/plot.png");
}

function ortho_bar_graph($data) {
    // loop through data and create countries and visits arrays
    $title = "Country Bar Graph";
    $countries = array();
    $visits = array();
    //if $data is empty
    if(empty($data)) {
        $countries = array("CA", "US", "RU", "CH", "SW", "AU", "DE", "JA", "NZ", "TEST");
        $visits = array(100,1000,5000,10000,100,1000,5000,10000,100,50000);
        $title = "Nulled Bar Graph";
    } else {
        foreach ($data as $key => $value) {
            array_push($countries, $key);
            array_push($visits, $value);
        }
    }

    $data1y=$visits;
    
    // Create the graph. These two calls are always required
    $graph = new Graph(350,200,'auto');
    $graph->SetScale("textlin");
    
    $theme_class=new UniversalTheme;
    $graph->SetTheme($theme_class);
    
    $graph->yaxis->SetTickPositions(array(0,1000,5000,10000,20000,30000,50000,100000,500000,1000000,5000000,10000000), array(150,4500,7500,10000,40000));
    $graph->SetBox(false);
    
    $graph->ygrid->SetFill(false);
    $graph->xaxis->SetTickLabels($countries);
    $graph->yaxis->HideLine(false);
    $graph->yaxis->HideTicks(false,false);
    
    // Create the bar plots
    $b1plot = new BarPlot($data1y);
    
    // Create the grouped bar plot
    $gbplot = new GroupBarPlot(array($b1plot));
    // ...and add it to the graPH
    $graph->Add($gbplot);
    
    
    $b1plot->SetColor("white");
    $b1plot->SetFillColor("#cc1111");
    
    $graph->title->Set($title);
    
    // draw the graph
    $graph->Stroke(GARY_PLUGIN_URI . "includes/analytics/graphs/bar.png");
}

function ortho_pie_graph($data) {
    $title = "7 Day Visits";
    if(empty($data)) {
        $data = array(40,60,21,33);
        $title = "null graph";
    }
 
    $graph = new PieGraph(300,200);
    $graph->SetShadow();
    
    $graph->title->Set($title);
    
    $p1 = new PiePlot($data);
    $graph->Add($p1);
    $graph->Stroke(GARY_PLUGIN_URI . "includes/analytics/graphs/pie.png");
}
?>