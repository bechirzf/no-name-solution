 describe("Go to the Homepage ", function(){
      browser.ignoreSynchronization=true; // This allows to protractor to run on regular website, not specific to angular 
      it("Go to the Home page ", function(){
        browser.get("http://impower/webs/GridView.aspx"); // Go to a specific URL
        console.log("Home Page Open succesfully") // Log a message
      });

    // From the Home Page go to the  Pendant Page 
    it("Filter Page ", function(){
        var mainFilters = element(by.cssContainingText('option', 'All Open Claims')); // set a variable for action
        mainFilters.click();// Click on element
        var btnExportToExcel = element(by.id('ExcelExport')); // set a variable for action
        btnExportToExcel.click();// Click on element
        var glob = require("glob");
        browser.sleep(5000) // Tell Protractor to Wait for 5000 miliseconds or 5sec
        var timeout = 30000;
        var filePattern = 'C:/Users/usmatr06/Downloads/*.xls';
        browser.driver.wait(function () {
            var filesArray = glob.sync(filePattern);
            if (typeof filesArray !== 'undefined' && filesArray.length > 0) {
                // this check is necessary because `glob.sync` can return
                // an empty list, which will be considered as a valid output
                // making the wait to end.
                return filesArray;
            }
        }, timeout).then(function (filesArray) {
            var filename = filesArray[0];
            var today = new Date();
            var date = (today.getMonth() + 1)+ "" + today.getDate()+ "" + today.getFullYear();
            var newFilename = 'C:/Users/usmatr06/Downloads/US Claim Potential RAW '+date+'.html';
            var fs = require('fs');
            fs.rename(filename, newFilename , function(err) {
                if ( err ) console.log('ERROR: ' + err);
            });
            // now we have the filename and can do whatever we want
        });
   });

  });