install.packages(c('rvest'),repos = "https://cloud.r-project.org/" )
library(rvest)

theurl <- "http://en.wikipedia.org/wiki/Brazil_national_football_team"
file<-read_html(theurl)
tables<-html_nodes(file, "table")
table1 <- html_table(tables[4], fill = TRUE)