import { expect } from 'chai';
import { Builder, By, until } from 'selenium-webdriver';
import { describe, it, after, before } from 'mocha';

let driver;

describe('Login Test', function() {
    this.timeout(30000); // Set timeout to 30 seconds

    before(async function() {
        driver = await new Builder().forBrowser('chrome').build();
    });

    it('should log in successfully', async function() {
        await driver.get('http://localhost/Tvflix/login.html');

        // Fill in the login form
        await driver.findElement(By.name('email')).sendKeys('testuser@example.com');
        await driver.findElement(By.name('pwd')).sendKeys('123');
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

        // Assert that the login was successful by checking for an element on the home page
        const element = await driver.findElement(By.id('body'));
        expect(await element.isDisplayed()).to.be.true;

        // Stay on the home page for at least 4 seconds
        await new Promise(resolve => setTimeout(resolve, 4000));
    });

    after(async function() {
        if (driver) {
            await driver.quit();
        }
    });
});
