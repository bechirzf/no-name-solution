# install.packages(c("XML",'RCurl','rlist'),repos = "https://cloud.r-project.org/" )
library(XML)
library(RCurl)
library(rlist)
theurl <- getURL("file:///C:/Users/usmatr06/Downloads/truth/Email%20Topia%20110218.html",.opts = list(ssl.verifypeer = FALSE) )
tables <- readHTMLTable(theurl)
tables <- list.clean(tables, fun = is.null, recursive = FALSE)
n.rows <- unlist(lapply(tables, function(t) dim(t)[1]))

directory <- "C:/Users/usmatr06/Desktop/emailTopiaData"

write.csv(n.rows, paste(directory,format(Sys.Date(),"%Y-%m"),".csv"))
