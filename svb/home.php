<?php
include_once"header.php";
?>
    <div class="container-fluid">
      <div class="content">
        <div class="row-fluid no-margin">
          <div class="span12"> 
          <div class="box" style="padding:10px;">
            <script type="text/javascript">
			$(function () {		
				function getNow () {
				   var now = new Date();
					return {
						hours: now.getHours(),
						minutes: now.getMinutes() * 12 / 60 + now.getSeconds() * 12 / 3600,
						seconds: now.getSeconds() * 12 / 60
					};
				};		
				var now = getNow();
				var chart = new Highcharts.Chart({
				
					chart: {
						renderTo: 'clock',
						type: 'gauge',
						plotBackgroundColor: null,
						plotBackgroundImage: null,
						plotBorderWidth: 0,
						plotShadow: false,
						height: 180
					},
					
					credits: {
						enabled: false
					},
					
					title: {
						text: ''
					},exporting: {
					 enabled: false
					},
					
					pane: {
						background: [{
						}, {
							backgroundColor: Highcharts.svg ? {
								radialGradient: {
									cx: 0.5,
									cy: -0.4,
									r: 1.9
								},
								stops: [
									[0.5, 'rgba(255, 255, 255, 0.2)'],
									[0.5, 'rgba(200, 200, 200, 0.2)']
								]
							} : null
						}]
					},
					
					yAxis: {
						labels: {
							distance: -20
						},
						min: 0,
						max: 12,
						lineWidth: 0,
						showFirstLabel: false,
						
						minorTickInterval: 'auto',
						minorTickWidth: 1,
						minorTickLength: 5,
						minorTickPosition: 'inside',
						minorGridLineWidth: 0,
						minorTickColor: '#666',
				
						tickInterval: 1,
						tickWidth: 2,
						tickPosition: 'inside',
						tickLength: 10,
						tickColor: '#666',
						title: {
							text: 'Spiffy <br/>Softs',
							style: {
								color: '#455460',
								fontWeight: 'normal',
								fontSize: '11px'               
							},
							y: 10
						}       
					},
				
					series: [{
						data: [{
							id: 'hour',
							y: now.hours,
							dial: {
								radius: '60%',
								baseWidth: 4,
								baseLength: '95%',
								rearLength: 0
							}
						}, {
							id: 'minute',
							y: now.minutes,
							dial: {
								baseLength: '95%',
								rearLength: 0
							}
						}, {
							id: 'second',
							y: now.seconds,
							dial: {
								radius: '100%',
								baseWidth: 1,
								rearLength: '20%'
							}
						}],
						animation: false,
						dataLabels: {
							enabled: false
						}
					}]
				}, 
												 
				// Move
				function (chart) {
					setInterval(function () {
						var hour = chart.get('hour'),
							minute = chart.get('minute'),
							second = chart.get('second'),
							now = getNow(),
							// run animation unless we're wrapping around from 59 to 0
							animation = now.seconds == 0 ? 
								false : 
								{
									easing: 'easeOutElastic'
								};
						hour.update(now.hours, true, animation);
						minute.update(now.minutes, true, animation);
						second.update(now.seconds, true, animation);
					}, 1000);
				
				});
});
		</script>
            <div id="clock"></div>
            <ul class="quicktasks">
              <?php
			  foreach($_SESSION['shortcuts']['image'] as $id=>$img)
			  {
			?>
              <li class="shortcuts" style=" font-size:12px;" id="shortcut<?php echo $_SESSION['shortcuts']['id'][$id] ?>"> <a href="<?php echo $_SESSION['shortcuts']['file_name'][$id] ?>"><div style="position:absolute; margin-top:-5px; margin-left:-10px"><span onClick="removeFav(event,<?php echo $_SESSION['shortcuts']['id'][$id] ?>)"><b>X</b></span></div>
                <?php
										echo $img;
										?>
                <span><b>
                <?php 
										echo $_SESSION['shortcuts']['name'][$id]; 
										?>
                </b> </span> </a>
               
                 </li>
              <?php
							}
							?>
              
            </ul>
            <span>*&nbsp;&nbsp;Add Ur Favouries From Right side of u'r page</span>    
          </div>
         </div>
        </div>
      </div>
    </div>

			
    <?php
include_once"footer.php";
?>