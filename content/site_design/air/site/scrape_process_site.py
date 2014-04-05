import re
import urllib
import httplib

pages = ["index"]

sections = { "overview": ["team", "client"],
			"research": ["overview", "stakeholders", "ca", "he", "findings"],
			"design": ["overview", "research", "vision", "ideation", "prototyping"],
			"solution": ["overview", "site_structure", "content", "organization", "presentation", "interaction"]}
			
for k, v in sections.iteritems():
	for x in v:
		pages.append(k + "_" + x)
	
	
for p in pages:
	url = "http://www.matthewmorosky.com/air/" + p + ".php"
	filename = p + ".html"
	
	text = None
	urlpage = urllib.urlopen(url)
	try:
		try:
			text = urlpage.read()
		finally:
			urlpage.close()
	except:
		pass
		
	text = re.sub(".php", ".html", text)
	
	f = open(filename, 'w')
	f.write(text)

	print "Wrote " + filename