describe("Go to the Homepage ", function(){
    browser.ignoreSynchronization=true; // This allows to protractor to run on regular website, not specific to angular 
    it("Go to the Main page ", function(){
        browser.get("https://cuic.corporate.ingrammicro.com:8444/cuicui/Main.jsp"); // Go to a specific URL
        console.log("Home Page Open succesffully") // Log a message
    });

    // From the Login Page go to the  Dashboard
    it("Login Page", function(){
        // var mainFilters = element(by.cssContainingText('option', 'All Open Claims')); // set a variable for action
        // mainFilters.click();// Click on element
        /*Login Actions*/
        var username = element(by.id('rawUserName')); // set a variable for action
        username.sendKeys('usmatr06');// Click on element
        var btnNext = element(by.id('identitySubmitBtn')); // set a variable for action
        btnNext.click();// Click on element 
        var password = element(by.id('j_password')); // set a variable for action
        password.sendKeys('cloud@999');// Click on element
        var domainOptions = element(by.cssContainingText('option', 'LDAP')); // set a variable for action
        domainOptions.click();// Click on element
        password.sendKeys(protractor.Key.ENTER);

        // browser.get('https://cuic.corporate.ingrammicro.com:8444/cuicui/Main.jsp#/reports');
        
        // var searchFilter = function(){
        //     //wrap browser wait functionality in a custom promise
        //     return new Promise(function(resolve) {
        //         var EC = protractor.ExpectedConditions, searchBar = element(by.id('searchFilter'));
        //         //wait on the url to change to the expected value
        //         browser.driver.wait(EC.visibilityOf(searchBar), 30000).then(function(){
        //             //resolve the promise only once the browser has confrimed the url changed
        //             resolve();
        //         });
        //     });
        // };
        // searchFilter().then(function(){
        //     var searchBar = element(by.id('searchFilter'));
        //     searchBar.sendKeys('Precision Queue and Agent');
        // });
       
        // // initialize page object
        // var navPage = element.all(by.repeater('page in pages'));

        // // variables
        // var maxLoopCount = '';
        // var i = 0;

        // // store number of lists into a variable
        // navPage.filter(function (page) {
        //     console.log(page)
        //     return false;
        //     // return column.evaluate("column.gadget").then(function (name) {
        //     //     console.log(name)
        //     //     return true;
        //     // });
        // }).then(function (rows) {
        //     console.log(rows)
        //     // if (sites) {  // we have a match - find and click the Delete button
        //     //     sites[0].element(by.linkText("Delete")).click();
        //     // }
        // });
        // nextPageAndCheck = function(page, pageCount) {
        //     if(page <= pageCount) {
        //         // home.paginationRight.click();
        //         var EC = protractor.ExpectedConditions,reportsLink = element(by.xpath("//span[. = 'Reports']"));
        //         browser.wait(EC.visibilityOf(navPage.reportsLink),1500, "not on this page").then(
        //            function(passed){return passed}, 
        //            function(error) {
        //               nextPageAndCheck(page++, pageCount);
        //            })
        //     } else {
        //        throw("couldn't find it error")
        //     }
        // }
        // var EC = protractor.ExpectedConditions, searchFilter = browser.driver.findElement(by.id('searchFilter'));
        // browser.wait(EC.visibilityOf(searchFilter), 10000);
        // browser.executeScript("arguments[0].scrollIntoView();", searchFilter.getWebElement());
        // browser.driver.findElement(by.id('searchFilter')).then().sendKeys('');
        // var list2 = element.all(by.repeater('csTabset.tabs'));
        
        // browser.wait(function() {
        //    return reportsLink.isPresent();
        // }, 10000);
        // reportsLink.click();
        // browser.waitForAngular(); 
        // expect(browser.getCurrentUrl()).toContain('#/reports');

            // var searchFilter = element(by.id('searchFilter'));
            // console.log(searchFilter.length)
            // .then(function() {
            //     var subReportsLink = element(by.xpath("//span[. = 'zzzCustom Reports']"));

            //     browser.wait(EC.visibilityOf(subReportsLink), 10000);
            //     subReportsLink.click().then(function() {
            //         var PrecAgent = element(by.xpath("//span[. = 'Precision Queue and Agent']"));

            //         browser.wait(EC.visibilityOf(PrecAgent), 10000);
            //         PrecAgent.click();
            //     });
            // });
        // var subMenuReportName = 'zzzCustom Reports';
 
        // var els = element.all(by.css('ngCellText name_cell_container colt0'));
        // els.filter(function(elem) {
        //   return elem.getText().then(function(text) {
        //     return text === subMenuReportName;
        //   });
        // }).click(); 

        // var btnSignIn = element(by.id('loginSubmit')); // set a variable for action
        // btnSignIn.click();// Click on element
        // browser.pause() // Tell Protractor to Wait for 5000 miliseconds or 5sec
        // element(by.css("#content > div:nth-child(2) > div:nth-child(1) > div.product-thumb.transition.options > div.caption > div >                 a")).click(); // Go the element and Click
        browser.sleep(10000);
        // console.log("Go to a specific Product Page");
        // browser.navigate().back(); // Tell the browser to go back

    });
});