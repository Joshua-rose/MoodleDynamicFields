// this will be called through the php
const paDomain = ['OIT', 'VBA', 'VHA'];
const caDomain = ['OITRegion', 'VBARegion', 'VHARegion'];
var showDomainChildren = new hideShow('Domain',paDomain,caDomain);
showDomainChildren.exec();
document.querySelector(`${FIELD_PREFIX}Domain`).addEventListener('change',
  function(){showDomainChildren.exec()});
console.log(document.querySelector(`${FIELD_PREFIX}Domain`));

const paOITRegion = ['Region 1','Region 2','Region 3','Region 4'];
const caOITRegion = ['VISN', 'VISN', 'VISN', 'VISN'];
var showOITRegionChildren = new hideShow('OITRegion', paOITRegion, caOITRegion);
showOITRegionChildren.exec();
document.querySelector(`${FIELD_PREFIX}OITRegion`).addEventListener('change',
  function(){showOITRegionChildren.exec()});

const paVBARegion = ['Region 1','Region 2','Region 3','Region 4'];
const caVBARegion = ['VISN', 'VISN', 'VISN', 'VISN'];
var showVBARegionChildren = new hideShow('VBARegion', paVBARegion, caVBARegion);
showVBARegionChildren.exec();
document.querySelector(`${FIELD_PREFIX}VBARegion`).addEventListener('change',
  function(){showVBARegionChildren.exec()});

const paVHARegion = ['Region 1','Region 2','Region 3','Region 4'];
const caVHARegion = ['VISN', 'VISN', 'VISN', 'VISN'];
var showVHARegionChildren = new hideShow('VHARegion', paVHARegion, caVHARegion);
showVHARegionChildren.exec();
document.querySelector(`${FIELD_PREFIX}VHARegion`).addEventListener('change',
  function(){showVHARegionChildren.exec()});
