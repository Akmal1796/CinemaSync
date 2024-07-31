import { expect } from 'chai';
import { Builder, By, until } from 'selenium-webdriver';
import { describe, it, after, before } from 'mocha';

let driver;

describe('Login Test', function() {
    this.timeout(60000); // Set timeout to 60 seconds

    before(async function() {
        driver = await new Builder().forBrowser('chrome').build();
    });

    it('Loggin successful', async function() {
        await driver.get('http://localhost/Tvflix/login.html');

        // Add a delay to observe the page loading
        await new Promise(resolve => setTimeout(resolve, 2000));

        // Fill in the login form
        await driver.findElement(By.name('email')).sendKeys('testuser@example.com');
        await new Promise(resolve => setTimeout(resolve, 1000)); // Add delay after entering email

        await driver.findElement(By.name('pwd')).sendKeys('123');
        await new Promise(resolve => setTimeout(resolve, 1000)); // Add delay after entering password

        await driver.findElement(By.css('input[type="submit"]')).click();

        // Handle the alert if it appears
        try {
            await driver.wait(until.alertIsPresent(), 5000);
            let alert = await driver.switchTo().alert();
            expect(await alert.getText()).to.equal('Login successful');
            await alert.accept();
        } catch (e) {
            // No alert appeared; continue as usual
        }

        // Wait for the redirection and check the result
        await driver.wait(until.urlIs('http://localhost/Tvflix/index.php'), 10000);

        // Add a delay to observe the redirected page
        await new Promise(resolve => setTimeout(resolve, 2000));

        // Assert that the login was successful by checking for an element on the home page
        const element = await driver.findElement(By.id('body'));
        expect(await element.isDisplayed()).to.be.true;

        // Add a delay to observe the profile icon
        await new Promise(resolve => setTimeout(resolve, 5000));

        // Click the profile icon
        await driver.findElement(By.id('profile-icon')).click();

        // Add a delay to observe the dropdown menu
        await new Promise(resolve => setTimeout(resolve, 1000));

        // Click the user name in the dropdown menu
        await driver.findElement(By.xpath("//a[@href='profile_edit.php']/button")).click();

        // Stay on the user profile page for at least 5 seconds
        await new Promise(resolve => setTimeout(resolve, 5000));
    });

    after(async function() {
        if (driver) {
            await driver.quit();
        }
    });
});
