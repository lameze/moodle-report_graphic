<?php
/*
* GERA GRAFICO DE ESTATISTICAS USANDO API GOOGLE  CHARTS
* CRIADO POR RANIELLY FERREIRA
* WWW.RFS.NET.BR 
* raniellyferreira@rfs.net.br
*
* v. 1.2.2 BETA
* ULTIMA MODIFICAÇÃO: 14/02/2013

** HISTÓRICO DE VERSÕES
1.0.0
- Criado
- Compatibilidade:
	- LineChart,PieChart,GeoChart,Gauge,TreeMap,CandlestickChart

1.0.1
- Corrigido bug na função array_to_jsarray();
- Corrigido bug na função array_to_jsobject();

1.1.4
- Adicionado parametro para alteração do tipo de grafico;
- Adicionado compatibilidade com:
	- ScatterChart, ComboChart, BarChart, ColumnChart, AreaChart 
- Função array_to_jsobject() melhorada;
- Função array_to_jsarray() melhorada;
- Adicionado a opção para criar div automaticamente;
- Função generate() melhorada;
- parametro TAG opcional, se nao setado à função generate(), ele cria id automaticamente, somente se a div for criada automaticamente;
- Função set_options() criada, para setar as opções, parametro options da função gerenate() foi removido;
- Adicionado opções para div de div_class, div_height, div_width, se create_div estiver TRUE, carregar com a função load();

1.1.5
- Correção de erros;

1.2.1
- Adicionado compatibilidade com Controls and Dashboards;

1.2.2
- Correção de erros;

-- COMPATÍVEL
	- LineChart
	- PieChart
	- GeoChart
	- Gauge
	- TreeMap
	- CandlestickChart
	- ScatterChart
	- ComboChart
	- BarChart
	- ColumnChart
	- AreaChart
	-- Controls and Dashboards
	
-- NÃO COMPATÍVEL
	- Table

*/

class Gcharts 
{
	public $library_loaded 		= FALSE;
	public $create_div			= TRUE;
	public $dashboard_div		= NULL;
	public $class_dashboard_div	= NULL;
	public $filter_div			= NULL;
	public $class_filter_div	= NULL;
	public $chart_div			= NULL;
	public $class_chart_div		= NULL;
	public $open_js_tag			= TRUE;
	public $graphic_type 		= 'LineChart'; //LineChart,PieChart,ColumnChart,AreaChart,TreeMap,ScatterChart,Gauge,GeoChart,ComboChart,BarChart,CandlestickChart,Table
	public $control_type		= 'NumberRangeFilter';
	private $gen_options 		= array();
	private $control_options 	= array();
	private $use_dashboard		= FALSE;
	
	function __contruct($array = array())
	{
		$this->load($array);
	}
	
	
	public function load_options($options = array())
	{
		if((bool) !$options)
		{
			return false;
		}
		
		$this->options = $options;
		return TRUE;
	}
	
	public function load($array = array())
	{
		if((bool) !$array)
		{
			return false;
		}
		
		foreach(array('library_loaded','graphic_type','create_div','dashboard_div','filter_div','chart_div','class_filter_div','class_dashboard_div','class_chart_div','open_js_tag','control_type') as $p)
		{
			if(isset($array[$p]))
			{
				if($p == 'graphic_type')
				{
					$this->set_graphic_type($array[$p]);
					continue;
				}
				$this->$p = $array[$p];
			}
		}
	}
	
	public function load_library()
	{
		if(!$this->library_loaded)
		{
			$this->library_loaded = TRUE;
			return '<script type="text/javascript" src="https://www.google.com/jsapi"></script>';
		}
		return NULL;
	}
	
	public function set_graphic_type($type = NULL)
	{
		if(is_null($type)) return false;
		
		$type = strtolower(trim($type));
		
		$types = array(
		'linechart' 		=> 'LineChart',
		'piechart' 			=> 'PieChart',
		'columnchart' 		=> 'ColumnChart',
		'areachart' 		=> 'AreaChart',
		'treemap' 			=> 'TreeMap',
		'scatterchart'		=> 'ScatterChart',
		'gauge' 			=> 'Gauge',
		'geochart' 			=> 'GeoChart',
		'combochart' 		=> 'ComboChart',
		'barchart' 			=> 'BarChart',
		'candlestickchart' 	=> 'CandlestickChart',
		'table' 			=> 'Table');
		
		if(!in_array($type,array_keys($types)))
		{
			exit('Error: Tipo de gráfico não é reconhecido. ['.$type.']');
		}
		
		$this->graphic_type = $types[$type];
		return true;
	}
	
	public function set_options($options = array())
	{
		if((bool) !$options)
		{
			return array();
		}
		
		$this->gen_options = $options;
		return true;
	}
	
	public function set_control_options($options = array())
	{
		if((bool) !$options)
		{
			return array();
		}
		
		$this->control_options = $options;
		return true;
	}
	
	public function generate($data)
	{
		if((bool) !$data)
		{
			return false;
		}
		
		if(is_null($this->chart_div))
		{
			$key = $this->gerarkey(10);
			$this->chart_div = 'gcharts_'.$key;
		}
		
		if($this->dashboard_div === TRUE)
		{
			$this->dashboard_div = 'dashboard_'.$key;
			$this->use_dashboard = TRUE;
		}
		
		if($this->filter_div === TRUE)
		{
			$this->filter_div = 'filter_'.$key;
		}
		
		if($this->use_dashboard === FALSE)
		{
			return $this->GenWithoutDashboard($data);
		} else
		{
			return $this->GenWithDashboard($data);
		}
		
		return false;
	}
	
	private function GenWithDashboard($data)
	{
		$js = NULL;
		
		$js .= $this->load_library()."\n";
		
		if($this->open_js_tag === TRUE)
		{
			$js .= '<script type="text/javascript">'."\n";
		}
		
		// Load the Visualization API and the controls package.
		$js .= 'google.load("visualization", "1", {packages:["controls"]});'."\n";
		
		// Set a callback to run when the Google Visualization API is loaded.
		$js .= 'google.setOnLoadCallback(drawDashboard);'."\n";
		
		// Callback that creates and populates a data table,
		// instantiates a dashboard, a range slider and a pie chart,
		// passes in the data and draws it.
		$js .= 'function drawDashboard() {'."\n";
		
		
		// Create our data table.
		$js .= 'var data = google.visualization.arrayToDataTable('.$this->array_to_jsarray($data).');'."\n";
		
		// Create a dashboard.
        $js .= "var dashboard = new google.visualization.Dashboard(document.getElementById('".$this->dashboard_div."'));\n";
		
		// Create a range slider, passing some options
        $js .= "var donutRangeSlider  = new google.visualization.ControlWrapper({
          'controlType': '".$this->control_type."',
          'containerId': '".$this->filter_div."',
		  'options': ".$this->array_to_jsobject($this->control_options)."});\n";
		
		// Create a pie chart, passing some options
        
        $js .= "var options = new google.visualization.ChartWrapper({
          'chartType': '".$this->graphic_type."',
          'containerId': '".$this->chart_div."',
          'options': ".$this->array_to_jsobject($this->gen_options)."});\n";
		
		// Establish dependencies, declaring that 'filter' drives 'pieChart',
        // so that the pie chart will only display entries that are let through
        // given the chosen slider range.
        $js .= "dashboard.bind(donutRangeSlider , options);\n";

        // Draw the dashboard.
        $js .= "dashboard.draw(data);\n";
		
		$js .= '}';
			
		if($this->open_js_tag === TRUE)
		{
        	$js .= '</script>'."\n";
		}
		
		/* CRIA AS DIVS */
		if($this->create_div === TRUE)
		{
			/* DASHBOARD DIV */
			if(!is_null($this->dashboard_div))
			{
				$js .= '<div id="'.$this->dashboard_div.'" class="'.$this->class_dashboard_div.'">';
			}
			
			/* FILTER DIV */
			if(!is_null($this->filter_div))
			{
				$js .= '<div id="'.$this->filter_div.'" class="'.$this->class_filter_div.'"></div>';
			}
			
			
			/* CHART DIV */
			$js .= '<div id="'.$this->chart_div.'" class="'.$this->class_chart_div.'"></div>';
			
			/* DASHBOARD CLOSE DIV */
			if(!is_null($this->dashboard_div))
			{
				$js .= '</div>';
			}
			
		} // FIM CREATE DIV
		$this->clean();
		return $js;
	}
	
	private function GenWithoutDashboard($data)
	{
		$js = NULL;
		
		$js .= $this->load_library()."\n";
		
		if($this->open_js_tag === TRUE)
		{
			$js .= '<script type="text/javascript">'."\n";
		}
		
		// Load the Visualization API and the controls package.
		$js .= 'google.load("visualization", "1", {packages:["corechart"]});'."\n";
		
		// Set a callback to run when the Google Visualization API is loaded.
		$js .= 'google.setOnLoadCallback(drawChart);'."\n";
		
		// Callback that creates and populates a data table,
		// instantiates a dashboard, a range slider and a pie chart,
		// passes in the data and draws it.
		$js .= 'function drawChart() {'."\n";
		
		// Create our data table.
		$js .= 'var data = google.visualization.arrayToDataTable('.$this->array_to_jsarray($data).');'."\n";
		
		//Generate the options.
		$js .= 'var options = '."\n";
		$js .= $this->array_to_jsobject($this->gen_options);
		$js .= ';'."\n";
		
		$js .= "var chart = new google.visualization.".$this->graphic_type."(document.getElementById('".$this->chart_div."'));\n";
		$js .= 'chart.draw(data, options);'."\n";
		$js .= '}';
			
		if($this->open_js_tag === TRUE)
		{
        	$js .= '</script>'."\n";
		}
		
		/* CRIA AS DIVS */
		if($this->create_div === TRUE)
		{
			/* CHART DIV */
			$js .= '<div id="'.$this->chart_div.'" class="'.$this->class_chart_div.'" style="width: 900px; height: 500px;"></div>';
		} // FIM CREATE DIV
		
		$this->clean();
		return $js;
	}
	
	/*
	@INPUT array:
	$array = array('title' => 'My Title');
	or
	$array = array('title' => 'My Title','vAxis' => array('title' => 'Cups'));
	
	@OUTPUT string:
	{title: 'title'}
	or
	{title: 'My Title',
	vAxis: {title: 'Cups'}}
	*/
	private function array_to_jsobject($array = array())
	{
		if((bool) !$array)
		{
			return '{}';
		}
		
		$return = NULL;
		foreach($array as $k => $v)
		{
			if(is_array($v))
			{
				$return .= $k.": ".$this->array_to_jsobject($v).",";
			} else
			{
				if(is_string($v))
				{
					$return .= $k.": '".addslashes($v)."',";
				} else
				{
					$return .= $k.": ".$v.",";
				}
			}
		}
		return '{'.trim($return,',').'}';
	}
	
	/*
	@INPUT matriz:
	$array = array(array('Year', 'Sales', 'Expenses'),
	array('2004',1000,400),
	array('2005',1170,460),
	array('2006',660,1120),
	array('2007',1030,540));
	
	@OUTPUT string:
	[['Year','Sales','Expenses'],['2004','1000','400'],['2005','1170','460'],['2006','660','1120'],['2007','1030','540']]
	*/
	private function array_to_jsarray($array = array())
	{
		if((bool) !$array)
		{
			return '[]';
		}
		
		$return = NULL;
		foreach($array as $k => $v)
		{
			if(is_array($v))
			{
				$return .= ','.$this->array_to_jsarray($v);
			} else
			{
				if(is_string($v))
				{
					$return .= ",'".addslashes($v)."'";
				} else
				{
					$return .= ",".$v;
				}
			}
		}
		
		return '['.trim($return,',').']';
	}
	
	public function clean()
	{
		//$this->library_loaded 		= FALSE;
		$this->create_div			= TRUE;
		$this->dashboard_div		= NULL;
		$this->class_dashboard_div	= NULL;
		$this->filter_div			= NULL;
		$this->class_filter_div		= NULL;
		$this->chart_div			= NULL;
		$this->class_chart_div		= NULL;
		$this->open_js_tag			= TRUE;
		$this->graphic_type 		= 'LineChart';
		$this->control_type			= 'NumberRangeFilter';
		$this->gen_options 			= array();
		$this->control_options 		= array();
		$this->use_dashboard		= FALSE;
	}
	
	public function is_number($num)
	{
		if((bool) preg_match("/^([0-9\.])+$/i",$num)) return true; else return false;
	}
	
	public function gerarkey($length = 40) 
	{
		$key = NULL;
		$pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRTWXYZ';
		for( $i = 0; $i < $length; ++$i )
		{
			$key .= $pattern{rand(0,58)};
		}
		return $key;
	}
	
}
?>