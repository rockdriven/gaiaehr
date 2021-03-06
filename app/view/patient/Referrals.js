/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

Ext.define('App.view.patient.Referrals', {
	extend: 'Ext.grid.Panel',
	requires: [
		'App.ux.LiveCPTSearch',
		'App.ux.LiveICDXSearch',
		'App.ux.combo.ActiveProviders',
		'Ext.selection.CheckboxModel',
		'App.ux.grid.RowFormEditing',
		'App.ux.combo.ReferringProviders'
	],
	xtype: 'patientreferralspanel',
	title: _('referrals'),
	action: 'referralsGrid',
	itemId: 'patientReferralsGrid',
	columnLines: true,
	allowDeselect: true,
	store: Ext.create('App.store.patient.Referrals', {
		remoteFilter: true
	}),
	plugins: [
		{
			ptype: 'rowformediting',
			clicksToEdit: 2,
			items: [
				{
					xtype: 'container',
					defaults: {
						layout: 'anchor'
					},
					layout: {
						type: 'hbox',
						align: 'stretch'
					},
					items: [
						{
							xtype: 'container',
							flex: 1,
							defaults: {
								labelAlign: 'right',
								margin: '0 0 5 0'
							},
							items: [
								{
									xtype: 'datefield',
									fieldLabel: _('referral_date'),
									name: 'referral_date',
									format: 'Y-m-d',
									validateBlank: true
								},
								{
									xtype: 'livecptsearch',
									fieldLabel: _('requested_service'),
									name: 'service_text',
									displayField: 'code_text',
									valueField: 'code_text',
									hideLabel: false,
									itemId: 'referralServiceSearch',
									anchor: '100%'
								},
								{
									xtype: 'textareafield',
									fieldLabel: _('reason'),
									name: 'referal_reason',
									anchor: '100%',
									height: 60
								},
								{
									xtype: 'liveicdxsearch',
									margin: '0 0 10',
									fieldLabel: _('diagnosis'),
									name: 'diagnosis_text',
									hideLabel: false,
									displayField: 'code_text',
									valueField: 'code_text',
									itemId: 'referralDiagnosisSearch',
									anchor: '100%'
								}
							]
						},
						{
							xtype: 'container',
							flex: 1,
							defaults: {
								labelAlign: 'right',
								margin: '0 0 5 0'
							},
							items: [
								{
									xtype: 'activeproviderscombo',
									fieldLabel: _('refer_by'),
									name: 'refer_by_text',
									width: 300,
									displayField: 'option_name',
									valueField: 'option_name',
									itemId: 'ReferralProviderCombo'
								},
								{
									xtype: 'container',
									layout: 'hbox',
									items: [
										{
											xtype: 'activeproviderscombo',
											fieldLabel: _('refer_to'),
											name: 'refer_to_text',
											labelAlign: 'right',
											margin: '0 5 5 0',
											width: 300,
//											disabled: true,
//											hidden: true,
											displayField: 'fullname',
											valueField: 'fullname',
											itemId: 'ReferralLocalProviderCombo'
										},
										{
											xtype: 'referringproviderscombo',
											fieldLabel: _('refer_to'),
											name: 'refer_to_text',
											labelAlign: 'right',
											margin: '0 5 5 0',
											width: 300,
											disabled: true,
											hidden: true,
											displayField: 'fullname',
											valueField: 'fullname',
											itemId: 'ReferralExternalProviderCombo'
										},
										{
											xtype: 'checkboxfield',
											boxLabel: _('external_referral'),
											itemId: 'ReferralExternalReferralCheckbox',
											name: 'is_external_referral'
										}
									]
								},

								{
									xtype: 'gaiaehr.combo',
									fieldLabel: _('risk_level'),
									name: 'risk_level',
									list: 17,
									width: 300
								},
//								{
//									xtype: 'checkboxfield',
//									fieldLabel: _('send_vitals'),
//									name: 'send_vitals',
//									width: 300
//								},
								{
									xtype: 'checkboxfield',
									fieldLabel: _('send_record'),
									name: 'send_record',
									width: 300
								}
							]
						}
					]
				}
			]
		}
	],
	selModel: Ext.create('Ext.selection.CheckboxModel'),
	columns: [
		{
			xtype: 'datecolumn',
			text: _('date'),
			dataIndex: 'referral_date',
			format: 'Y-m-d',
			menuDisabled: true,
			resizable: false
		},
		{
			text: _('refer_by'),
			dataIndex: 'refer_by_text',
			menuDisabled: true,
			resizable: false,
			width: 200
		},
		{
			text: _('refer_to'),
			dataIndex: 'refer_to_text',
			menuDisabled: true,
			resizable: false,
			width: 200
		},
		{
			text: _('request'),
			dataIndex: 'referal_reason',
			menuDisabled: true,
			resizable: false,
			flex: 1
		}
	],
	tbar: [
		'->',
		{
			text: _('referral'),
			iconCls: 'icoAdd',
			itemId: 'encounterRecordAdd',
			action: 'addReferralBtn'
		},
		'-',
		{
			text: _('print'),
			iconCls: 'icoPrint',
			disabled: true,
			itemId: 'printReferralBtn'
		}
	]
});