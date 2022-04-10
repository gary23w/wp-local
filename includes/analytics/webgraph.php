<?php
function ortho_line_graph($data) {
        // Some data
        $ydata = $data;             
        // Create the graph. These two calls are always required
        $graph = new Graph(750,650);
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
    $width=750;
    $height=650;
    
    // Create the graph and set a scale.
    // These two calls are always required
    $graph = new Graph($width,$height, 'auto');
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
    $title = "Countries.";
    $countries = array();
    $visits = array();
    //if $data is empty
    if(empty($data)) {
        $countries = array("CA", "US", "RU", "CH", "SW", "AU", "DE", "JA", "NZ", "TEST");
        $visits = array(100,1000,5000,10000,100,1000,5000,10000,100,5000);
        $title = "Nulled Bar Graph";
    } else {
        foreach ($data as $key => $value) {
            array_push($countries, $key);
            array_push($visits, $value);
        }
    }

    $data1y=$visits;
    
    // Create the graph. These two calls are always required
    $graph = new Graph(620,500,'auto');
    $graph->SetScale("textlin");
    
    $theme_class=new UniversalTheme;
    $graph->SetTheme($theme_class);
    
    $graph->yaxis->SetTickPositions($visits);
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
    if(array_sum($data) == 0) {
        $data = array(100,1000,5000,10000,100,1000,5000,10000,100,50000);
        $title = "Nulled Pie Graph";
    }
    if(empty($data)) {
        $data = array(40,60,21,33);
        $title = "null graph";
    }
 
    $graph = new PieGraph(700,600, 'auto');
    $graph->SetShadow();
    $amount = array();
    $p1 = new PiePlot($data);
    $dates = array();
    for($i = 0; $i < count($data); $i++) {
        $date1 = new DateTime(); 
        $date1->modify("-" . $i . " day");
        $date1 = $date1->format("Y-m-d");
        $date1 = $date1 . " - " . $data[$i];
        array_push($dates, $date1);
    }

    $p1->SetLegends($dates);

    $p1->SetGuideLines(true,false);
    $p1->SetGuideLinesAdjust(1.1);
    $p1->SetLabelType(PIE_VALUE_PER);    
    $p1->value->Show();              
    $p1->value->SetFormat('%2.1f%%');
    $graph->Add($p1);
    $graph->Stroke(GARY_PLUGIN_URI . "includes/analytics/graphs/pie.png");
}
?>