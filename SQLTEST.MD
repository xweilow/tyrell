## SQL Improvement Logic Test

1) WHERE clause with multiple tables greatly lower query performance
2) OR and LIKE both prevents use of indexes
3) Index all columns used in “WHERE”, “ORDER BY”, “GROUP BY”, “ON” clause
4) Apply FULLTEXT index to search columns

###Suggestions

5) Receive an input that indicates which field to search for, so there’s no need to search for multiple tables.

6) Select all the matched job_id using UNION clause first instead of joining in one single query.
```php
SELECT jp.job_id as job_id
FROM personalities p
INNER JOIN jobs_personalities jp ON (jp.personality_id = p.id AND p.deleted IS NULL)
WHERE p.name LIKE '%キャビンアテンダント%'
UNION
SELECT jps.job_id as job_id
FROM practical_skills ps
INNER JOIN jobs_practical_skills jps ON (jps.practical_skill_id = ps.id AND ps.deleted IS NULL)
WHERE ps.name LIKE '%キャビンアテンダント%'
UNION
SELECT jba.job_id as job_id
FROM basic_abilities ba
INNER JOIN jobs_basic_abilities jba ON (jba.basic_ability_id = ba.id AND ba.deleted IS NULL)
WHERE ba.name LIKE '%キャビンアテンダント%'
UNION
SELECT jt.job_id as job_id
FROM tools t
INNER JOIN jobs_tools jt ON (jt.affiliate_id = t.id AND t.type = 1 AND t.deleted IS NULL)
WHERE t.name LIKE '%キャビンアテンダント%'
UNION
SELECT jcp.job_id as job_id
FROM career_paths cp
INNER JOIN jobs_career_paths jcp ON (jcp.affiliate_id = cp.id AND cp.type = 3 AND cp.deleted IS NULL)
WHERE cp.name LIKE '%キャビンアテンダント%'
UNION
SELECT jrq.job_id as job_id
FROM rec_qualifications rq
INNER JOIN jobs_rec_qualifications jrq ON (jrq.affiliate_id = rq.id AND rq.type = 2 AND rq.deleted IS NULL)
WHERE rq.name LIKE '%キャビンアテンダント%'
UNION
SELECT jreq.job_id as job_id
FROM req_qualifications req
INNER JOIN jobs_req_qualifications jreq ON (jreq.affiliate_id = req.id AND req.type = 2 AND req.deleted IS NULL)
WHERE req.name LIKE '%キャビンアテンダント%'
GROUP BY job_id

```

7) Replace “?” in the query with the above job_id. This query includes lesser table joins and where clause.

```php
SELECT Jobs.id AS `Jobs__id`,
Jobs.name AS `Jobs__name`,
Jobs.media_id AS `Jobs__media_id`,
Jobs.job_category_id AS `Jobs__job_category_id`,
Jobs.job_type_id AS `Jobs__job_type_id`,
Jobs.description AS `Jobs__description`,
Jobs.detail AS `Jobs__detail`,
Jobs.business_skill AS `Jobs__business_skill`,
Jobs.knowledge AS `Jobs__knowledge`,
Jobs.location AS `Jobs__location`,
Jobs.activity AS `Jobs__activity`,
Jobs.academic_degree_doctor AS `Jobs__academic_degree_doctor`,
Jobs.academic_degree_master AS `Jobs__academic_degree_master`,
Jobs.academic_degree_professional AS `Jobs__academic_degree_professional`,
Jobs.academic_degree_bachelor AS `Jobs__academic_degree_bachelor`,
Jobs.salary_statistic_group AS `Jobs__salary_statistic_group`,
Jobs.salary_range_first_year AS `Jobs__salary_range_first_year`,
Jobs.salary_range_average AS `Jobs__salary_range_average`,
Jobs.salary_range_remarks AS `Jobs__salary_range_remarks`,
Jobs.restriction AS `Jobs__restriction`,
Jobs.estimated_total_workers AS `Jobs__estimated_total_workers`,
Jobs.remarks AS `Jobs__remarks`,
Jobs.url AS `Jobs__url`,
Jobs.seo_description AS `Jobs__seo_description`,
Jobs.seo_keywords AS `Jobs__seo_keywords`,
Jobs.sort_order AS `Jobs__sort_order`,
Jobs.publish_status AS `Jobs__publish_status`,
Jobs.version AS `Jobs__version`,
Jobs.created_by AS `Jobs__created_by`,
Jobs.created AS `Jobs__created`,
Jobs.modified AS `Jobs__modified`,
Jobs.deleted AS `Jobs__deleted`,
JobCategories.id AS `JobCategories__id`,
JobCategories.name AS `JobCategories__name`,
JobCategories.sort_order AS `JobCategories__sort_order`,
JobCategories.created_by AS `JobCategories__created_by`,
JobCategories.created AS `JobCategories__created`,
JobCategories.modified AS `JobCategories__modified`,
JobCategories.deleted AS `JobCategories__deleted`,
JobTypes.id AS `JobTypes__id`,
JobTypes.name AS `JobTypes__name`,
JobTypes.job_category_id AS `JobTypes__job_category_id`,
JobTypes.sort_order AS `JobTypes__sort_order`,
JobTypes.created_by AS `JobTypes__created_by`,
JobTypes.created AS `JobTypes__created`,
JobTypes.modified AS `JobTypes__modified`,
JobTypes.deleted AS `JobTypes__deleted`
FROM jobs Jobs
INNER JOIN job_categories JobCategories ON (JobCategories.id = Jobs.job_category_id AND JobCategories.deleted IS NULL)
INNER JOIN job_types JobTypes ON (JobTypes.id = Jobs.job_type_id AND JobTypes.deleted IS NULL)
WHERE (
JobCategories.name LIKE '%キャビンアテンダント%'
OR
JobTypes.name LIKE '%キャビンアテンダント%'
OR
Jobs.id IN (?)
OR
MATCH (Jobs.name, Jobs.description, Jobs.detail, Jobs.business_skill, Jobs.knowledge, Jobs.location, Jobs.activity,  Jobs.salary_statistic_group, Jobs.salary_range_remarks,  Jobs.restriction, Jobs.remarks) AGAINST ('%キャビンアテンダント%' IN NATURAL LANGUAGE MODE)
)
AND publish_status = 1 AND Jobs.deleted IS NULL
GROUP BY Jobs.id
ORDER BY Jobs.sort_order desc, Jobs.id DESC
LIMIT 50 OFFSET 0
```
