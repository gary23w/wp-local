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

function ortho_line_plot($datax, $datay) {
    $ydata = array(11,3,8,12,5,1,9,13,5,7);
    $y2data = array(354,200,265,99,111,91,198,225,293,251);
    
    // Create the graph and specify the scale for both Y-axis
    $graph = new Graph(300,240);    
    $graph->SetScale("textlin");
    $graph->SetShadow();
    
    // Adjust the margin
    $graph->img->SetMargin(40,40,20,70);
    
    // Create the two linear plot
    $lineplot=new LinePlot($ydata);
    $lineplot->SetStepStyle();
    
    // Adjust the axis color
    $graph->yaxis->SetColor("blue");
    
    $graph->title->Set("Example 6.2");
    $graph->xaxis->title->Set("X-title");
    $graph->yaxis->title->Set("Y-title");
    
    $graph->title->SetFont(FF_FONT1,FS_BOLD);
    $graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
    $graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
    
    // Set the colors for the plots 
    $lineplot->SetColor("blue");
    $lineplot->SetWeight(2);
    
    // Set the legends for the plots
    $lineplot->SetLegend("Plot 1");
    
    // Add the plot to the graph
    $graph->Add($lineplot);
    
    // Adjust the legend position
    $graph->legend->SetLayout(LEGEND_HOR);
    $graph->legend->Pos(0.4,0.95,"center","bottom");
    
    // draw the graph
    $graph->Stroke(GARY_PLUGIN_URI . "includes/analytics/graphs/plot.png");
}

function ortho_bar_graph($data) {
    // loop through data and create countries and visits arrays
    $countries = array();
    $visits = array();
    //if $data is empty
    if(empty($data)) {
        $countries = array("CA", "US", "RU", "CH", "SW", "AU", "DE", "JA", "NZ", "TEST");
        $visits = array(100,1000,5000,10000,100,1000,5000,10000,100,50000);
    } else {
        foreach ($data as $key => $value) {
            //add key to countries array
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
    
    $graph->title->Set("Bar Plots");
    
    // draw the graph
    $graph->Stroke(GARY_PLUGIN_URI . "includes/analytics/graphs/bar.png");
}
?>