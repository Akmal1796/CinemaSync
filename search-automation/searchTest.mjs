import { expect } from 'chai';
import { Builder, By, until } from 'selenium-webdriver';
import { describe, it, before, after } from 'mocha';

let driver;

describe('Movie Search Test', function() {
    this.timeout(60000); // Set timeout to 60 seconds

    before(async function() {
        driver = await new Builder().forBrowser('chrome').build();
    });

    after(async function() {
        await driver.quit();
    });

    it('should search for movies and display results', async function() {
        try {
            await driver.get('http://localhost/Tvflix/index.php');

            // Wait for the search field to be present and visible
            const searchField = await driver.wait(until.elementLocated(By.css('[search-field]')), 10000);
            await driver.wait(until.elementIsVisible(searchField), 10000);

            // Add delay to ensure the search field is fully interactive
            await driver.sleep(1000);

            // Enter the search term
            await searchField.sendKeys('Despicable Me');

            // Add delay to wait for the search to complete
            await driver.sleep(2000);

            // Wait for search results to appear
            const searchResultModel = await driver.wait(until.elementLocated(By.css('.search-model')), 10000);
            await driver.wait(until.elementIsVisible(searchResultModel), 10000);

            // Add delay to ensure search results are fully loaded
            await driver.sleep(1000);

            // Verify search results are displayed
            const searchResults = await searchResultModel.findElement(By.css('.grid-list'));
            const movies = await searchResults.findElements(By.css('.movie-card'));
            expect(movies.length).to.be.greaterThan(0);

            // Get the title of the first movie card
            const firstMovieTitle = await movies[0].findElement(By.css('.title')).getText();
            console.log('First Movie Title:', firstMovieTitle);

            // Add delay before clicking the movie card
            await driver.sleep(1000);

            // Click on the first movie card
            await movies[0].click();

            // Add delay to wait for the details page to load
            await driver.sleep(2000);

            // Wait for the movie details page to load
            await driver.wait(until.urlContains('detail.php'), 10000);

            // Add delay to ensure movie details are fully loaded
            await driver.sleep(1000);

            // Verify that the movie details page is displayed
            const movieTitleElement = await driver.wait(until.elementLocated(By.css('h1.heading')), 10000);
            const titleText = await movieTitleElement.getText();
            console.log('Movie Title Text:', titleText);

            // Add delay before asserting the title
            await driver.sleep(1000);

            // Assert that the movie title matches the one from the search results
            expect(titleText).to.equal(firstMovieTitle);
        } catch (error) {
            // Print the page source for debugging
            const pageSource = await driver.getPageSource();
            console.log('Page Source:', pageSource);
            throw error;
        }
    });
});
