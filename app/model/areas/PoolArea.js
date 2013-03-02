/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, inc.

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

Ext.define('App.model.areas.PoolArea', {
	extend   : 'Ext.data.Model',
	table: {
		name:'poolarea',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Pool Area'
	},
	fields   : [
		{name: 'pid', type: 'int'},
		{name: 'eid', type: 'int'},
		{name: 'name', type: 'string'},
		{name: 'shortName', type: 'string'},
		{name: 'photoSrc', type: 'string'},
		{name: 'poolArea', type: 'string'},
		{name: 'floorPlanId', type: 'int'},
		{name: 'zoneId', type: 'int'},
		{name: 'patientZoneId', type: 'int'},
		{name: 'priority', type: 'string'},
		{name: 'rank',  type:'int', }
	],
	proxy    : {
		type       : 'direct',
		api        : {
			read: PoolArea.getPatientsByPoolAreaAccess
		}
	}
});