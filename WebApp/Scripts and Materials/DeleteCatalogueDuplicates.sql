/* 
To Select all cataloguedata duplicates
SELECT image_url, item_name, COUNT(*)
 FROM y0vryqAKXK.cataloguedata
 GROUP BY image_url, item_name
 HAVING COUNT(*) > 1; 
 
 */
-- Deleting dupes

DELETE FROM y0vryqAKXK.cataloguedata
WHERE catalogueid IN (
SELECT catalogueid
FROM (
select catalogueid,
ROW_NUMBER() OVER (PARTITION BY product_url, image_url, item_name) AS rownum FROM y0vryqAKXK.cataloguedata
) AS sub
WHERE rownum > 1
);

/* 
To select all combinedreview duplicates
SELECT image_url, item_name, customername, rating_score, review_location, review_date, COUNT(*)
 FROM y0vryqAKXK.combinedreview
 GROUP BY image_url, item_name, customername, rating_score, review_location, review_date
 HAVING COUNT(*) > 1; 
 */
 
DELETE FROM y0vryqAKXK.combinedreview
WHERE combinedreviewid IN (
SELECT combinedreviewid
FROM (
select combinedreviewid,
ROW_NUMBER() OVER (PARTITION BY image_url, item_name, customername, rating_score, review_location, review_date) AS rownum FROM y0vryqAKXK.combinedreview
) AS sub
WHERE rownum > 1
);
