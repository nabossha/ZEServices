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
	<script src="JS/angular.min.js" type="text/javascript"></script>
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
								<div class="vehicleshortstate"><span class="remainingCharge">{{vehicle.CruisingRange}}</span>km &mdash; <span class="remainingCharge">{{vehicle.BatteryRemainingPercent}}</span>%</div>
								<div class="battery">
									<div class="battery-level" ng-class="setChargeDisplayColor(vehicle.BatteryRemainingPercent)" ng-style="setChargeDisplay(vehicle.BatteryRemainingPercent)"></div>
								</div>
							</td>
						</tr>
						<tr>
							<td><span class="tablerowsmalltitle">Status am {{vehicle.OperationDateAndTime|date:'d. MMMM HH:mm'}}</span>
								<span ng-if="vehicle.PluginState=='NOT_CONNECTED'" class="red">nicht verbunden</span>
								<span ng-else-if="vehicle.PluginState=='CONNECTED'" class="green">verbunden</span>
								<span ng-else class="orange">{{vehicle.PluginState}}</span>
								/
								<span ng-if="vehicle.OperationResultType=='CHARGENOTINPROGRESS'" class="red">lädt nicht</span>
								<span ng-else-if="vehicle.OperationResultType=='CHARGEINPROGRESS'" class="green">wird geladen</span>
								<span ng-else class="orange">{{vehicle.OperationResultType}} <br>({{vehicle.BatteryChargingSpot}})</span>

								<span ng-if="vehicle.OperationResultType=='CHARGEINPROGRESS' && vehicle.BatteryChargingSpot=='SEMI_FAST_CHARGING'">
									<br>(beschleunigtes Laden)
								</span>
								<span ng-if="vehicle.OperationResultType=='CHARGEINPROGRESS' && vehicle.BatteryChargingSpot=='SLOW_CHARGING'">
									<br>(langsames Laden)
								</span>
							</td>
						</tr>
						<tr>
							<td>
								<div ng-if="vehicle.OperationResultType=='CHARGEINPROGRESS'">
								<span class="tablerowsmalltitle">Beginn des aktuellen Ladevorgangs:</span> {{vehicle.LastChargeStartDate|date:'d. MMMM HH:mm'}}
								</div>
								<div ng-else>
								<span class="tablerowsmalltitle">Ende letzter Ladevorgang:</span> {{vehicle.LastChargeEndDate|date:'d. MMMM HH:mm'}} ({{vehicle.LastChargePercentage}}%)
								</div>
							</td>
						</tr>
						</tbody>
					</table>
				</div>


			</div>
		</div>
	</div>
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