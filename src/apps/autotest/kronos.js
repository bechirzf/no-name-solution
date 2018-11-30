describe("Go to the Homepage ", function(){
      browser.ignoreSynchronization=true; // This allows to protractor to run on regular website, not specific to angular 
      it("Go to the Home page ", function(){
        browser.get("https://timeentry.corporate.ingrammicro.com/wfc/logon"); // Go to a specific URL
        console.log("Home Page Open succesffully") // Log a message
      });

    // From the Home Page go to the  Pendant Page 
    it("Filter Page ", function(){
        // var mainFilters = element(by.cssContainingText('option', 'All Open Claims')); // set a variable for action
        // mainFilters.click();// Click on element
       
        var username = element(by.id('username')); // set a variable for action
        username.sendKeys('usmatr06');// Click on element
        var password = element(by.id('passInput')); // set a variable for action
        password.sendKeys('');// Click on element
        var btnLogin = element(by.id('loginSubmit')); // set a variable for action
        btnLogin.click();// Click on element
        // browser.pause() // Tell Protractor to Wait for 5000 miliseconds or 5sec
        // element(by.css("#content > div:nth-child(2) > div:nth-child(1) > div.product-thumb.transition.options > div.caption > div >                 a")).click(); // Go the element and Click
        // browser.sleep(1000)
        // console.log("Go to a specific Product Page");
        browser.sleep(10000)
        // browser.navigate().back(); // Tell the browser to go back

   });

  });