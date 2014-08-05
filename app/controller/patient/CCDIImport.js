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

Ext.define('App.controller.patient.CCDImport', {
	extend: 'Ext.app.Controller',
	requires: [

	],
	refs: [
		{
			ref: 'CcdImportWindow',
			selector: 'ccdimportwindow'
		},
		{
			ref: 'CcdImportPatientForm',
			selector: '#CcdImportPatientForm'
		},
		{
			ref: 'CcdImportEncounterForm',
			selector: '#CcdImportEncounterForm'
		},
		{
			ref: 'CcdImportEncounterAssessmentContainer',
			selector: '#CcdImportEncounterAssessmentContainer'
		},

		// grids...
		{
			ref: 'CcdImportMedicationsGrid',
			selector: '#CcdImportMedicationsGrid'
		},
		{
			ref: 'CcdImportAllergiesGrid',
			selector: '#CcdImportAllergiesGrid'
		},
		{
			ref: 'CcdImportProceduresGrid',
			selector: '#CcdImportProceduresGrid'
		},
		{
			ref: 'CcdImportActiveProblemsGrid',
			selector: '#CcdImportActiveProblemsGrid'
		},
		{
			ref: 'CcdImportOrderResultsGrid',
			selector: '#CcdImportOrderResultsGrid'
		},
		{
			ref: 'CcdImportEncountersGrid',
			selector: '#CcdImportEncountersGrid'
		},

		// buttons...
		{
			ref: 'CcdImportWindowImportBtn',
			selector: '#CcdImportWindowImportBtn'
		},
		{
			ref: 'CcdImportWindowCloseBtn',
			selector: '#CcdImportWindowCloseBtn'
		},
		{
			ref: 'CcdImportWindowPatientSearchField',
			selector: '#CcdImportWindowPatientSearchField'
		}
	],

	init: function(){
		var me = this;

		me.control({
			'ccdimportwindow': {
				show: me.onCcdImportWindowShow
			},
			'#CcdImportWindowImportBtn': {
				click: me.onCcdImportWindowImportBtnClick
			},
			'#CcdImportWindowCloseBtn': {
				click: me.onCcdImportWindowCloseBtnClick
			},
			'#CcdImportWindowPatientSearchField': {
				select: me.onCcdImportWindowPatientSearchFieldSelect
			},
			'#CcdImportWindowSelectAllField': {
				change: me.onCcdImportWindowSelectAllFieldChange
			},
			'#CcdImportWindowViewRawCcdBtn': {
				click: me.onCcdImportWindowViewRawCcdBtnClick
			}
		});

	},

	onCcdImportWindowShow: function(win){
		this.doLoadCcdData(win.ccdData);
	},

	doLoadCcdData: function(data){
		var me = this;

		var pForm = me.getCcdImportPatientForm().getForm(),
			ePanel = me.getCcdImportEncounterForm(),
			eForm = ePanel.getForm(),
			patient = Ext.create('App.model.patient.Patient', data.patient);


		if(data.encounter && !Ext.Object.isEmpty(data.encounter)){
			ePanel.show();
			var encounter = Ext.create('App.model.patient.Patient', data.encounter),
				assessmentContainer = me.getCcdImportEncounterAssessmentContainer();

			say(encounter);

			eForm.loadRecord(encounter);

			for(var i = 0; i < data.encounter.assessments.length; i++){
				assessmentContainer.add({
					anchor: '100%',
					boxLabel: data.encounter.assessments[i].text,
					assessmentData: data.encounter.assessments[i],
					boxLabelCls: 'CheckBoxWrapHammerFix'
				});
			}

		}else{
			ePanel.hide();
		}



		pForm.loadRecord(patient);

		// list 59 ethnicity
		// list 14 race

		if(data.patient.race && data.patient.race != ''){
			CombosData.getDisplayValueByListIdAndOptionValue(14, data.patient.race, function(response){
				pForm.findField('race_text').setValue(response);
			});
		}

		if(data.patient.ethnicity && data.patient.ethnicity != ''){
			CombosData.getDisplayValueByListIdAndOptionCode(59, data.patient.ethnicity, function(response){
				pForm.findField('ethnicity_text').setValue(response);
			});
		}

		if(data){
			if(data.allergies && data.allergies.length > 0){
				me.reconfigureGrid('getCcdImportAllergiesGrid', data.allergies);
			}
			if(data.medications && data.medications.length > 0){
				me.reconfigureGrid('getCcdImportMedicationsGrid', data.medications);
			}
			if(data.problems && data.problems.length > 0){
				me.reconfigureGrid('getCcdImportActiveProblemsGrid', data.problems);
			}
			if(data.procedures && data.procedures.length > 0){
				me.reconfigureGrid('getCcdImportProceduresGrid', data.procedures);
			}
			if(data.results && data.results.length > 0){
				me.reconfigureGrid('getCcdImportOrderResultsGrid', data.results);
			}
			if(data.encounters && data.encounters.length > 0){
				me.reconfigureGrid('getCcdImportEncountersGrid', data.encounters);
			}
		}
	},

	reconfigureGrid: function(getter, data){
		var me = this,
			grid = me[getter]();
		grid.getStore().loadRawData(data);
	},

	onCcdImportWindowImportBtnClick: function(){

	},

	onCcdImportWindowCloseBtnClick: function(){
		this.getCcdImportWindow().close();
	},

	onCcdImportWindowPatientSearchFieldSelect: function(cmb, records){

		say(records);

	},

	doResultShowObservations: function(store){

	},

	onCcdImportWindowSelectAllFieldChange: function(field, selected){
		var me = this,
			grids = me.getCcdImportWindow().query('grid');

		for(var i = 0; i < grids.length; i++){
			var sm = grids[i].getSelectionModel();
			if(selected){
				sm.selectAll();
			}else{
				sm.deselectAll();
			}
		}

		if(me.getCcdImportEncounterAssessmentContainer()){
			var checkboxes = me.getCcdImportEncounterAssessmentContainer().query('checkbox');

			for(var j = 0; j < checkboxes.length; j++){
				checkboxes[j].setValue(selected);
			}

		}
	},

	onCcdImportWindowViewRawCcdBtnClick: function(){
		var me = this,
			record = Ext.create('App.model.patient.PatientDocumentsTemp', {
				create_date: new Date(),
				document_name: 'temp_ccd.xml',
				document: me.getCcdImportWindow().ccd
			});

		record.save({
			callback: function(record){
				app.onDocumentView(record.data.id, 'ccd');
				say(record.data.id);

			}
		});
	}
});