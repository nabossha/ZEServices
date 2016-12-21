<!DOCTYPE html>
<html lang="de" xmlns:og="http://ogp.me/ns#" ng-app="vehiclestatusApp" manifest="cache.manifest">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-title" content="Z.E.Garage">
	<meta name="format-detection" content="telephone=no">
	<link rel="apple-touch-icon" href="Styles/apple-touch-icon.png">
	<!-- iPhone 5 startup image -->
	<link href="Styles/startup-iphone5.png"
	      media="(device-width: 320px) and (device-height: 568px)
                 and (-webkit-device-pixel-ratio: 2)"
	      rel="apple-touch-startup-image">
	<!-- iPhone 6 startup image -->
	<link href="Styles/startup.png"
	      media="(device-width: 375px) and (device-height: 667px)
                 and (-webkit-device-pixel-ratio: 2)"
	      rel="apple-touch-startup-image">
	<link rel="stylesheet" type="text/css" href="Styles/normalize.css">
	<link rel="stylesheet" type="text/css" href="Styles/skeleton.css">
	<link rel="stylesheet" type="text/css" href="Styles/xpull.css">
	<link rel="stylesheet" type="text/css" href="Styles/custom.css">
	<script src="JS/jquery-2.1.3.min.js" type="text/javascript"></script>
	<script src="JS/moment-with-locales.js" type="text/javascript"></script>
	<script src="JS/angular.min.js" type="text/javascript"></script>
	<script src="JS/angular-moment.min.js"></script>
	<script src="JS/angular-locale_de-ch.js"></script>
	<script src="JS/elif.js" type="text/javascript"></script>
	<script src="JS/xpull.js" type="text/javascript"></script>
	<script src="JS/controllers.js" type="text/javascript"></script>
	<title>Z.E.Garage</title>
</head>
<body data-ng-controller="VehicleListCtrl">
<div id="page" ng-xpull="reload()">
	<div class="section banner">
		<div class="container">
			<div class="row">
				<h1>Z.E. Garage</h1><span class="lastupdatefromapi">{{LastUpdateFromAPI|date:'dd.MM. yyyy HH:mm'}}</span>
			</div>
		</div>
	</div>


	<div class="section values">
		<div class="container">
			<div class="row">
				<div class="one-half column" ng-repeat="vehicle in vehicles">
					<table class="u-full-width">
						<tbody>
						<tr>
							<td class="tableoverviewrow">
								<h2 class="vehiclename">{{vehicle.VehicleName}}</h2>
								<div class="vehicleshortstate"><span class="remainingCharge">{{vehicle.remaining_range}}</span>km &mdash; <span class="remainingCharge">{{vehicle.charge_level}}</span>%</div>
								<div class="battery">
									<div class="battery-level" ng-class="setChargeDisplayColor(vehicle.charge_level)" ng-style="setChargeDisplay(vehicle.charge_level)"></div>
								</div>
							</td>
						</tr>
						<tr>
							<td><span class="tablerowsmalltitle">Status am {{vehicle.last_update|date:'d. MMMM HH:mm'}}</span>
								<span ng-if="vehicle.plugged==false" class="red">nicht verbunden</span>
								<span ng-else-if="vehicle.plugged==true" class="green">verbunden</span>
								<span ng-else class="orange">{{vehicle.plugged}}</span>
								/
								<span ng-if="vehicle.charging==false" class="red">lädt nicht</span>
								<span ng-else-if="vehicle.charging==true" class="green">
									<span ng-if="vehicle.charging==true && vehicle.charging_point=='ACCELERATED'">beschleunigtes Laden</span>
									<span ng-if="vehicle.charging==true && vehicle.charging_point=='SLOW'">langsames Laden</span>
								</span>
								<span ng-else class="orange">{{vehicle.charging}} <br>({{vehicle.charging_point}})</span>

								<span ng-if="vehicle.charging==true">
									<br>(verbleibend ca. {{vehicle.remaining_time}} Minuten / Ende ca. {{ vehicle.last_update | amAdd: vehicle.remaining_time:'minutes' | amDateFormat: 'HH:mm' }})
								</span>
								<div class="calendaricon" ng-if="vehicle.next.on_board_calendar_status=='ACTIVATED'"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 17.016 17.016" style="enable-background:new 0 0 17.016 17.016;" xml:space="preserve" width="32px" height="32px">
<g><g><path d="M16.122,1.578h-2.051v2.529c0,0.566-0.508,1.027-1.133,1.027h-0.773    c-0.625,0-1.133-0.46-1.133-1.027V1.578H5.984v2.529c0,0.566-0.509,1.027-1.133,1.027H4.077c-0.625,0-1.133-0.46-1.133-1.027    V1.578H0.892c-0.341,0-0.618,0.277-0.618,0.618v14.201c0,0.34,0.277,0.619,0.618,0.619h15.23c0.342,0,0.619-0.279,0.619-0.619    V2.196C16.742,1.855,16.464,1.578,16.122,1.578z M15.906,16.147c0,0.105-0.088,0.193-0.195,0.193H1.305    c-0.107,0-0.195-0.088-0.195-0.193V7.393c0-0.107,0.087-0.195,0.195-0.195H15.71c0.107,0,0.195,0.088,0.195,0.195L15.906,16.147    L15.906,16.147z" fill="#44a300"/>
		<path d="M4.077,4.121H4.85c0.284,0,0.515-0.183,0.515-0.408V0.408C5.365,0.182,5.134,0,4.85,0H4.077    C3.792,0,3.561,0.182,3.561,0.408v3.306C3.561,3.938,3.792,4.121,4.077,4.121z" fill="#44a300"/>
		<path d="M12.165,4.121h0.773c0.285,0,0.516-0.183,0.516-0.408V0.408c0-0.226-0.23-0.408-0.516-0.408h-0.773    c-0.285,0-0.516,0.182-0.516,0.408v3.306C11.651,3.938,11.88,4.121,12.165,4.121z" fill="#44a300"/>
		<path d="M12.047,8.406c-0.135-0.134-0.352-0.134-0.486,0l-4.03,4.032l-2.076-2.092    c-0.134-0.133-0.35-0.133-0.484,0l-0.728,0.727c-0.133,0.133-0.133,0.352,0,0.484l3.043,3.064c0.133,0.133,0.351,0.133,0.485,0    l5.003-5.004c0.135-0.133,0.135-0.352,0-0.486L12.047,8.406z" fill="#44a300"/>
	</g></g></svg></div>
							</td>
						</tr>
						<tr>
							<td>
								<div ng-if="vehicle.previous.type=='START_NOTIFICATION'">
								<span class="tablerowsmalltitle">Beginn des aktuellen Ladevorgangs:</span> {{vehicle.previous.date|date:'d. MMMM HH:mm'}}
								</div>
								<div ng-else>
								<span class="tablerowsmalltitle">Ende letzter Ladevorgang:</span> {{vehicle.previous.date|date:'d. MMMM HH:mm'}} ({{vehicle.previous.remaining_autonomy}}km / {{vehicle.previous.charge_level}}%)
								</div>
							</td>
						</tr>
						</tbody>
					</table>
				</div>


			</div>
		</div>
	</div>
	<div id="errorwindow"></div>
	<button class="centered loadbutton" data-ng-click="loadServerData('event')">
		<span>Daten aktualisieren</span>
		<div class="spinner">
			<div class="rect1"></div>
			<div class="rect2"></div>
			<div class="rect3"></div>
			<div class="rect4"></div>
			<div class="rect5"></div>
		</div>
	</button>
</div>

</body>
</html>