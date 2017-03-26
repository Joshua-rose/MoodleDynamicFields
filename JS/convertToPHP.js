// this will be called through the php
const paHS_Domain = ['OIT', 'VBA', 'VHA'];
const caHS_Domain = ['OITRegion', 'VBARegion', 'VHARegion'];
var showDomainChildren = new hideShow('Domain',paHS_Domain,caHS_Domain);
showDomainChildren.exec();
document.querySelector(`${FIELD_PREFIX}Domain`).addEventListener('change',
  function(){showDomainChildren.exec()});
console.log(document.querySelector(`${FIELD_PREFIX}Domain`));

const paHS_OITRegion = ['Region 1','Region 2','Region 3','Region 4'];
const caHS_OITRegion = ['VISN', 'VISN', 'VISN', 'VISN'];
var showOITRegionChildren = new hideShow('OITRegion', paHS_OITRegion, caHS_OITRegion);
showOITRegionChildren.exec();
document.querySelector(`${FIELD_PREFIX}OITRegion`).addEventListener('change',
  function(){showOITRegionChildren.exec()});

const paHS_VBARegion = ['Region 1','Region 2','Region 3','Region 4'];
const caHS_VBARegion = ['VISN', 'VISN', 'VISN', 'VISN'];
var showVBARegionChildren = new hideShow('VBARegion', paHS_VBARegion, caHS_VBARegion);
showVBARegionChildren.exec();
document.querySelector(`${FIELD_PREFIX}VBARegion`).addEventListener('change',
  function(){showVBARegionChildren.exec()});

const paHS_VHARegion = ['Region 1','Region 2','Region 3','Region 4'];
const caHS_VHARegion = ['VISN', 'VISN', 'VISN', 'VISN'];
var showVHARegionChildren = new hideShow('VHARegion', paHS_VHARegion, caHS_VHARegion);
showVHARegionChildren.exec();
document.querySelector(`${FIELD_PREFIX}VHARegion`).addEventListener('change',
  function(){showVHARegionChildren.exec()});

const paUP_VISN = ['Region 1','Region 1','Region 1','Region 1','Region 1',
                'Region 2','Region 2','Region 2','Region 2','Region 2','Region 2',
                'Region 3','Region 3','Region 3','Region 3','Region 3','Region 3',
                'Region 4','Region 4','Region 4','Region 4','Region 4','Region 3',];
const caUP_VISN = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23'];
var updateVISN_OITRegion = new update('OITRegion','VISN',paUP_VISN,caUP_VISN,'Select VISN1');
//updateVISN.exec();
document.querySelector(FIELD_PREFIX + 'OITRegion').addEventListener('change',
  function(){updateVISN_OITRegion.exec()});

var updateVISN_VBARegion = new update('VBARegion','VISN',paUP_VISN,caUP_VISN,'Select VISN2');
//updateVISN.exec();
document.querySelector(FIELD_PREFIX + 'VBARegion').addEventListener('change',
  function(){updateVISN_VBARegion.exec()});

var updateVISN_VHARegion = new update('VHARegion','VISN',paUP_VISN,caUP_VISN);
//updateVISN.exec();
document.querySelector(FIELD_PREFIX + 'VHARegion').addEventListener('change',
  function(){updateVISN_VHARegion.exec()});
const paHS_VISN = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23'];
const caHS_VISN = ['FacilityName', 'FacilityName','FacilityName','FacilityName','FacilityName','FacilityName','FacilityName','FacilityName','FacilityName','FacilityName',
               'FacilityName','FacilityName','FacilityName','FacilityName','FacilityName','FacilityName','FacilityName','FacilityName','FacilityName','FacilityName',
               'FacilityName','FacilityName','FacilityName'];
var showVISNChildren = new hideShow('VISN',paHS_VISN,caHS_VISN);
showVISNChildren.exec();
document.querySelector(`${FIELD_PREFIX}VISN`).addEventListener('change',
  function(){showVISNChildren.exec()});

const caUP_FacilityName = [ "VA New England Healthcare System (Bedford, MA) ","Edith Nourse Rogers Memorial Veterans Hospital (Bedford VA) (Bedford, MA)","Manchester VA Medical Center (Manchester, NH)","Providence VA Medical Center (Providence, RI)","VA Boston Healthcare System, Brockton Campus (Brockton, MA)","VA Boston Healthcare System, Jamaica Plain Campus (Jamaica Plain, MA)","VA Boston Healthcare System, West Roxbury Campus (West Roxbury, MA)","VA Central Western Massachusetts Healthcare System (Leeds, MA)","VA Connecticut Healthcare System, Newington Campus (Newington, CT)","VA Connecticut Healthcare System, West Haven Campus (West Haven, CT)",
"VA Maine Healthcare System - Togus (Augusta, ME)","White River Junction VA Medical Center (White River Junction, VT)","Causeway OPC (Boston, MA)","Fort Kent Access Point Clinic (Fort Kent, ME)","Houlton Satellite Clinic (Houlton, ME)","Mobile Medical Unit (Bingham, ME)","Aroostook County (Caribou) Community Based Outpatient Clinic (Caribou, ME)","Bangor Community Based Outpatient Clinic  (Bangor, ME)","Bennington Outpatient Clinic (Bennington, VT)","Brattleboro Community Based Outpatient Clinic (Brattleboro, VT)","Burlington Outpatient Lakeside Clinic (Burlington, VT)",
"Calais Outpatient Clinic (Calais, ME)","Conway Outpatient Clinic (Conway, NH)","Danbury Outpatient Clinic (Danbury, CT)","Fitchburg Outpatient Clinic (Fitchburg, MA)","Framingham Outpatient Clinic (Framingham, MA)","Gloucester Community Based Outpatient Clinic (CBOC) (Gloucester, MA)","Greenfield Outpatient Clinic (Greenfield, MA)","Haverhill Community Based Outpatient Clinic (CBOC) (Haverhill, MA)","Hyannis Outpatient Clinic (Hyannis, MA)","John J. McGuirk (New London) VA Outpatient Clinic (New London, CT)","Keene Outpatient Clinic (Keene, NH)",
"Lewiston/ Auburn Community Based Outpatient Clinic (Lewiston, ME)","Lincoln Community Based Outpatient Clinic (Lincoln, ME)","Littleton Community Based Outpatient Clinic (Littleton, NH)","Lowell Outpatient Clinic (Lowell, MA)","Lynn Community Based Outpatient Clinic (CBOC) (Lynn, MA)","Middletown Outpatient Clinic (Middletown, RI)","New Bedford Outpatient Clinic (New Bedford, MA)","Newport Community Based Outpatient Clinic (Newport, VT)","Pittsfield Outpatient Clinic (Pittsfield, MA)","Plymouth Outreach Clinic (Plymouth, MA)","Portland Community Based Outpatient Clinic (Portland, ME)",
"Portsmouth Outpatient Clinic (Portsmouth, NH)","Quincy Outpatient Clinic (Quincy, MA)","Rumford Community Based Outpatient Clinic (Rumford, ME)","Rutland Community Based Outpatient Clinic (Rutland, VT)","Saco Community Based Outpatient Clinic (Saco, ME)","Somersworth Outpatient Clinic (Somersworth, NH)","Springfield Outpatient Clinic (Springfield, MA)","Stamford Outpatient Clinic (Stamford, CT)","Tilton Outpatient Clinic (Tilton, NH)","Waterbury Outpatient Clinic (Waterbury, CT)","Willimantic Outpatient Clinic (Willimantic, CT)",
"Winsted Outpatient Clinic (Winsted, CT)","Worcester Outpatient Clinic (Worcester, MA)","1A RCS Northeast Regional Office (Auburn, NH)","Bangor Vet Center (Bangor, ME)","Berlin Vet Center (Gorham, NH)","Boston Vet Center (Boston, MA)","Brockton Vet Center (Brockton, MA)","Cape Cod Vet Center (Hyannis, MA)","Caribou Vet Center (Caribou, ME)","Danbury Vet Center (Danbury, CT)","Hartford Vet Center (Rocky Hill, CT)",
"Keene Vet Center Outstation (Keene, NH)","Lewiston Vet Center (Lewiston, ME)","Lowell Vet Center (Lowell, MA)","Manchester Vet Center (Hooksett, NH)","New Bedford Vet Center (Fairhaven, MA)","New Haven Vet Center (Orange, CT)","Norwich Vet Center (Norwich, CT)","Portland Vet Center (Portland, ME)","Providence Vet Center (Warwick, RI)","Sanford Vet Center (Springvale, ME)",
"South Burlington Vet Center (South Burlington, VT)","Springfield Vet Center (West Springfield, MA)","White River Junction Vet Center (White River Junction, VT)","Worcester Vet Center (Worcester, MA)","VA Health Care Upstate New York	(Albany, NY)  ","Albany VA Medical Center: Samuel S. Stratton (Albany, NY) ","Bath VA Medical Center (Bath, NY) ","Canandaigua VA Medical Center (Canandaigua, NY) ","Syracuse VA Medical Center (Syracuse, NY) ","VA Western New York Healthcare System at Batavia (Batavia, NY) ","VA Western New York Healthcare System at Buffalo (Buffalo, NY) ",
"Behavioral Health Facility (Syracuse, NY) ","Auburn VA Outpatient Clinic (Auburn, NY) ","Bainbridge VA Outpatient Clinic (Bainbridge, NY) ","Binghamton VA Outpatient Clinic (Binghamton, NY) ","CANI (Watertown, NY) ","Catskill VA Outpatient Clinic (Catskill, NY) ","Clifton Park VA Outpatient Clinic (Clifton Park, NY) ","Coudersport (Coudersport, PA) ","Dunkirk VA Outpatient Clinic (Dunkirk, NY) ","Elmira VA Outpatient Clinic (Elmira, NY) ","Fonda VA Outpatient Clinic (Fonda, NY) ",
"Glens Falls VA Outpatient Clinic (Glens Falls, NY) ","Jamestown VA Outpatient Clinic (Jamestown, NY) ","Kingston VA Outpatient Clinic (Kingston, NY) ","Lackawanna VA Outpatient Clinic (Lackawanna, NY) ","Lockport VA Outpatient Clinic (Lockport, NY) ","Malone VA Outpatient Clinic (Malone, NY) ","Massena VA Outpatient Clinic (Massena, NY) ","Niagara Falls VA Outpatient Clinic (Niagara Falls, NY) ","Olean VA Outpatient Clinic (Olean, NY) ","Oswego VA Outpatient Clinic (Oswego, NY) ","Plattsburgh VA Outpatient Clinic (Plattsburgh, NY) ",
"Rochester VA Outpatient Clinic (Rochester, NY) ","Rome - Donald J. Mitchell VA Outpatient Clinic (Rome, NY) ","Saranac Lake (Saranac Lake, NY) ","Schenectady VA Outpatient Clinic (Schenectady, NY) ","Springville (Springville, NY) ","Tompkins/Cortland County (Freeville, NY) ","Troy VA Outpatient Clinic (Troy, NY) ","Watertown VA Outpatient Clinic (Watertown, NY) ","Wellsboro (Wellsboro, PA) ",
"Wellsville VA Outpatient Clinic (Wellsville, NY) ","Westport (Westport, NY) ","Albany Vet Center (Albany, NY) ","Binghamton Vet Center (Binghamton, NY) ","Buffalo Vet Center (Buffalo, NY) ","Rochester Vet Center (Rochester, NY) ","Syracuse Vet Center (Syracuse, NY) ","Watertown Vet Center (Watertown, NY)"];
const paUP_FacilityName = ["1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","1","2","3","4","5","6","7","8","9","10"];

var updateFacilityName_VISN = new update('VISN','FacilityName',paUP_FacilityName,caUP_FacilityName, 'Select Facility1');
document.querySelector(FIELD_PREFIX + 'VISN').addEventListener('change', function(){
  updateFacilityName_VISN.exec();
});
