#---------------just need to run once during installation--------
#install devtools package for downloading packages from github
#install.packages("devtools",repos = "https://cran.stat.upd.edu.ph/", type="binary")
#install.packages(c('devtools','curl','RCurl','bitops','jsonlite','httr'),repos = "https://cloud.r-project.org/")
library(devtools)
#install curl for easier use
#install.packages("curl",repos = "https://cran.stat.upd.edu.ph/")
#installing rga package from github
install_github("skardhamar/rga")
library(rga)
rga.open(instance = "ga",where="C:/Users/usmatr06/Desktop/Tasks/GA/ga.rga")
# rga.open(instance = "ga", 
#          client.id = "305153569924-lficdt93iq07mr3h78uhofftq286cq94.apps.googleusercontent.com", 
#          client.secret = "-tMVuZeWyC-yCEppCWNUuwb7")
id <- 119784873
#----------------------------------------------------------------

#----Initialize variables----------------------------------------
	# ------Monthly Auto Run----------------------------------------------
endDate <- as.Date(format(Sys.Date(),"%Y-%m-01")) - 1
startDate <- as.Date(format(endDate,"%Y-%m-01"))
#endDate <- seq(startDate, by = "+1 month", length = 2)[2] - 1
	# ------Custom Range----------------------------------------------
# endDate <- as.Date("2018-02-28")
# startDate <- as.Date("2018-02-01")


gaDatawalk <- ga$getData(id, start.date = startDate, 
                 end.date=endDate, metrics = "ga:pageviews,ga:entrances,ga:timeOnPage,
                 ga:avgTimeOnPage,ga:uniquePageviews,ga:exits,ga:bounces,ga:sessions",
                 dimensions = "ga:date,ga:pagePath",
                 sort = "-ga:pageviews", batch = TRUE, walk = TRUE)



##---- Development ---------------
# directory <- "C:/Users/username/Desktop/Tasks/GA/gaData"
##---- Staging -------------------
directory <- "//USBFFNP22/Share/TS/MGTADMIN/Yochum/Agent Metrics/RAW/GA/Test/gaData"
##---- Production ----------------
# directory <- "//USBFFNP22/Share/TS/MGTADMIN/Yochum/Agent Metrics/RAW/GA/gaData"


write.csv(gaDatawalk, paste(directory,format(startDate,"%Y-%m"),".csv"))
