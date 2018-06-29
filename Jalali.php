<?php
/**
 * @property Calendar $calendar
 */

class Jalali
{
private $CI;
private $db;
public $year;
public $month;
public $day;
public $hour;
public $minute;
public $secound;
private $day_func;
private $prev_month;
private $prev_year;
private $next_month;
private $next_year;
private $next_year_on;
private $prev_year_on;
private $prev_month_btn = '';
private $prev_year_btn = '';
private $next_month_btn = '';
private $next_year_btn = '';
private $attrs = '';
private $holidays = false;
    private $attrs_span = '';
    public function __construct()
    {
       $this->CI  = &get_instance();
       $this->db = &$this->CI->db;
       $this->year = pdate('Y');
       $this->month = pdate('m');
       $this->day   = pdate('d');
       $this->hour  = pdate('G');
       $this->minute= pdate('i');
       $this->secound= pdate('s');
       $this->day_func = array();
       $this->navs();
    }

	
	public function set_holidays($array){
		/*
		array(
		'1397'=>array(
    "1-1" => "عید نوروز",
"1-2" => "عید نوروز",
"1-3" => "عید نوروز",
"1-4" => "عید نوروز",
"1-11" => "ولادت حضرت علی (ع)",
"1-12" => "روز جمهوری اسلامی ایران",
"1-13" => "روز طبیعت",
"1-25" => "مبعث حضرت رسول اکرم (ص)",
"2-12" => "ولادت حضرت مهدی (عج)",
"3-14" => "رحلت امام خمینی (ره)",
"3-15" => "قیام خونین 15 خرداد",
"3-16" => "شهادت حضرت علی (ع)",
"3-25" => "عید سعید فطر",
"3-26" => "تعطیل به مناسبت عید سعید فطر",
"4-18" => "شهادت حضرت امام جعفر صادق (ع)",
"5-31" => "عید سعید قربان",
"6-8" => "عید غدیر خم",
"6-28" => "تاسوعای حسینی",
"6-29" => "عاشورای حسینی",
"8-8" => "اربعین حسینی",
"8-16" => "رحلت حضرت رسول اکرم (ص) و شهادت حضرت امام حسن مجتبی (ع)",
"8-17" => "شهادت حضرت امام رضا علیه السلام",
"9-4" => "ولادت حضرت رسول اکرم",
"11-20" => "شهادت حضرت فاطمه زهرا (س)",
"11-22" => "پیروزی انقلاب اسلامی ایران و سقوط نظام شاهنشاهی",
"12-29" => "روز ملی شدن صنعت نفت",
);
		
		)
		*/
		if(!empty)
		$this->holidays =$array;
		

	}
    public function set_attrs_span($array){
        foreach($array as $k=>$v){
            $this->attrs_span[$k] = $v;

        }


    }
public function set_attrs($array){
    foreach($array as $k=>$v){
       $this->attrs[$k] = $v;

    }


}
/////////////////////////////////////////////////////////////
public function get_week_day_name(){
     return pdateWeekName();


}

private function navs(){
$this->prev_month ='';
$this->prev_year ='';
$this->next_year ='';
$this->next_month ='';
    $this->next_year_on = $this->year;
    $this->prev_year_on = $this->year;
    $this->prev_month = (int)$this->month - 1;
    $this->prev_year =  $this->year - 1;
    $this->next_month = (int)$this->month + 1;
    $this->next_year =  $this->year + 1;

   if($this->prev_month==0){
       $this->prev_month = 12;
       $this->prev_year_on--;
   }
    if($this->next_month==13){
        $this->next_month = 1;
        $this->next_year_on++;
    }



}

public function prev_month($str=''){
$this->prev_month_btn =  str_replace(array('#MONTH#','#YEAR#'),array($this->prev_month,$this->prev_year_on),$str);

}
    public function prev_year($str=''){
        $this->prev_year_btn = str_replace(array('#MONTH#','#YEAR#'),array(1,$this->prev_year),$str);

    }
    public function next_year($str=''){
        $this->next_year_btn= str_replace(array('#MONTH#','#YEAR#'),array(1,$this->next_year),$str);

    }
    public function next_month($str=''){
        $this->next_month_btn = str_replace(array('#MONTH#','#YEAR#'),array( $this->next_month,$this->next_year_on),$str);

    }

    public function make_nav($a){
    $this->navs();
        foreach($a as $k=>$v){
          $this->{$k}($v);

        }


    }

    public function get_text(){
        $d = pmktime(0,0,0,$this->month,1,$this->year);
$c = pdate('F',$d).' '.pdate('Y',$d);;

$b = $this->next_year_btn.'  '. $this->next_month_btn.'  '.$c.'  '.$this->prev_month_btn.'  '.$this->prev_year_btn;

        return $b;


    }

    public function is_this_day($day){

        if(pdate('Y')==$this->year && (int)pdate('m') == (int)$this->month && (int)pdate('d') == $day){

return true;

        }
return false;
    }

public function day_func_add($year,$month,$day,$data){

$this->day_func[$year.'_'.(int)$month.'_'.(int)$day][] = $data;

}
public function load_tds(){
$out = '';
    $month = (int)$this->month;
    $year = $this->year;
$D = new stdclass();

    $D->week = pdate("W");
    $D->firstDayOfMonth = pmktime(0,0,0,$month,1,$year);
    $D->numberDays = pdate('t',$D->firstDayOfMonth);
    $D->dateComponents = pgetdate($D->firstDayOfMonth);

    $D->monthName = $D->dateComponents['month'];
    $D->dayOfWeek = $D->dateComponents['wday'];
$out .='<tr>';
$day_plus = $D->dayOfWeek;
$attr = '';
    $attr_span = '';
    if(!isset($this->attrs['class'])){
     $this->set_attrs(array('class'=>''));
    }
    if($this->attrs ){

        pr($attr);
foreach($this->attrs  as $k=>$v){

    $attr .= $k.'='.'"'.(str_replace(array("#YEAR#","#MONTH#","#DAY#"),array($year,$month,'#DAY#'),$v )).'"' ;
}

    }
    if($this->attrs_span ){
        foreach($this->attrs_span  as $k=>$v){

            $attr_span .= $k.'='.'"'.(str_replace(array("#YEAR#","#MONTH#","#DAY#"),array($year,$month,'#DAY#'),$v )).'"' ;
        }

    }
for($day=1 ; $day <=$D->numberDays  ; $day++){
    if( $D->dayOfWeek && $day==1){
        $out .= '<td data-disabled= "1" class="bg-danger" colspan="'.$D->dayOfWeek.'" id="td_cal_pre_'.$year.'_'.$month.'_'.$day.'" >'."\n";
        $D->dayOfWeek = 0;
    }
    $attr_span_p = '';
    $attr_p = '';
if($attr)
    $attr_p = str_replace('#DAY#',$day,$attr);
if($attr_span)
    $attr_span_p = str_replace('#DAY#',$day,$attr_span);



if($this->holidays && isset($this->holidays[$year][$month.'-'.$day])  ){
    $attr_p=  preg_replace('/class\s*\=\s*\"(.*)\"/ius','class="$1 tatilat_td"', $attr_p);
    $this->day_func[$year.'_'.$month.'_'.$day][] = '<b onclick="alert(\''.$this->holidays[$year][$month.'-'.$day].'\')"  class="label label-danger tatilat_b">تعطیل</b>';
}elseif($day_plus==6){
    $attr_p=  preg_replace('/class\s*\=\s*\"(.*)\"/ius','class="$1 tatilat_td"', $attr_p);
    $this->day_func[$year.'_'.$month.'_'.$day][] = '<b   class="label label-danger tatilat_b">تعطیل</b>';

}


    $out .= '<td '.$attr_p.' data-year="'.$year.'" data-month="'.$month.'" data-day="'.$day.'"     id="td_cal_'.$year.'_'.$month.'_'.$day.'" >'."\n";
    $out .='<span '.$attr_span_p.' class="cal_td_span '.($this->is_this_day($day) ? 'thisday' : '' ).'"  >'.$day.'</span>'."\n";
  if(!empty($this->day_func) && isset($this->day_func[$year.'_'.$month.'_'.$day])){
      foreach($this->day_func[$year.'_'.$month.'_'.$day] as $kf=>$f){
          $out .= '<b class="add_func">'.$f.'</b>';

      }

  }

    $out .='</td>'."\n";
        $day_plus++;
        if($day_plus==7){
            $day_plus=0;
            $out .= '</tr><tr>';
        }

}



 return $out ;
}

    public function load($data=false){



	$o =  '<div class="row">
<div class="col-xs-12">
<div class="responsive">
    <!---main--->
    <div class="form-group text-center">
      <?= $this->calendar->get_text(); ?>
    </div>
<table class="calendar_table table table-bordered table-responsive">
<thead>
<tr>';

  
    foreach($this->get_week_day_name() as $k=>$v){
    $o .= '<th id="calendar_weekname_'.($k+1).'">'.$v.'</th>'."\n";
    }
  
$o .='</tr>
</thead>
<tbody>
'. $this->load_tds().'
</tbody>
</table>
<!---main--->
</div>
</div>
</div>';
	
	return $o;
	

    }

}