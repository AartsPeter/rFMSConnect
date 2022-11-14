<?php

 function _format_json($json, $html = false) {
		$tabcount = 0;
		$tabarrayactive = 0;
		$result = '';
		$inquote = false;
		$ignorenext = false;

		if ($html) {
		    $tab = "&nbsp;&nbsp;&nbsp;";
		    $tabarray = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		    $newline = "<br/>";
		} else {
		    $tab = "\t";
		    $tabarray = "\t\t";
		    $newline = "\n";
		}

		for($i = 0; $i < strlen($json); $i++) {
		    $char = $json[$i];

		    if ($ignorenext) {
		        $result .= $char;
		        $ignorenext = false;
		    } else {
		        switch($char) {
		            case '{':
		                $tabcount++;
//		                $result .=  $newline . str_repeat( $tabarray,$tabarrayactive) . $char .  str_repeat($tab, $tabcount);
		                $result .=   $newline . str_repeat($tab, $tabcount) . $char;
		                break;
		            case '}':
		                $tabcount--;
		                $result =  trim($result) . str_repeat($tab, $tabcount) . $char  ;

		                break;
		            case ',':
		                $result .= $char . $newline . str_repeat($tab, $tabcount);
		                break;
		            case '"':
		                $inquote = !$inquote;
		                $result .= $char;
		                break;
		            case '[':
		                $inquote = !$inquote;
		                $result .=  '<b>'.$char.' </b>';
		                $tabarrayactive++;
		                break;
		            case ']':
		                $tabarrayactive--;
		                $result =  str_repeat( $tabarray,$tabarrayactive). trim($result) .$newline. '<b> '.$char.'</b>'    ;
		                break;
		            case '\\':
		                if ($inquote) $ignorenext = true;
		                $result .= $char;
		                break;
		            default:
		                $result .= $char;
		        }
		    }
		}

		return $result;
	}

	function HTMLHeader(){
	    header("Content-Type: text/html");
        $str= '<html>
        <head>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet" >
            <script src="../plugins/jQuery/jquery-latest.min.js"></script>
            <script src="../js/rfms.js"></script>
			<style>
			    @media (max-width: 900px){
			        body : {font-size:75%;}
			    }
				.venster {height : calc(50vh - 100px);}
			</style>
        </head>
        <body class="bg-light "><div class="container-full ">';
        return $str;
 	}

 	function ShowDebugQuery($Query,$Result,$title='your query',$html=false){
     	$str = ' <div class=" d-flex  overflow-auto ">';
     	$str.= '    <div class="card shadow-sm col-4 p-0 m-3 mr-0">';
     	$str.= '     <div class="card-header d-flex"><div class="col"><B>'.$title.'</b><small> (query)</small></div><div><button class=" text-right btn btn-sm btn-secondary" onclick="CopyToClipBoard(`sqltext`)">Copy Query</button></div></div>';
     	$str.= '     <div class="card-body small overflow-auto" id="sqltext">'.SqlFormatter::format($Query,$html).'</div>';
        $str.= '    </div>';
        $str.= '    <div class="card shadow-sm col p-0 m-3">';
        $str.= '     <div class="card-header d-flex"><div class="col"><b>Result</b><small> as JSON </small></div> <div ><button class=" text-right btn btn-sm btn-secondary" onclick="CopyToClipBoard(`jsontext`)">Copy JSON</button></div></div>';
     	$str.= '     <div class="card-body venster overflow-auto  small" id="jsontext"><small>'._format_json(json_encode($Result),$html).'</small></div>';
        $str.= '    </div>';
     	$str.= ' </div></div>';
        return $str;
 	}

	?>