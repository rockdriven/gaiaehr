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

Ext.define('App.model.administration.PreventiveCareLabs', {
	extend: 'Ext.data.Model',
	table: {
		name:'preventivecarelabs',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Preventive Care Labs'
	},	
	fields: [
		{name: 'id', type: 'int'},
		{name: 'value_name', type: 'string'},
		{name: 'greater_than', type: 'string'},
		{name: 'less_than', type: 'string'},
		{name: 'equal_to', type: 'string'},
		{name: 'code', type: 'string'},
		{name: 'preventive_care_id', type: 'string'}
	]

});