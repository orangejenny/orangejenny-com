// Run "grunt watch" from public/ directory
module.exports = function(grunt) {
	grunt.initConfig({
		// running `grunt less` will compile once
		less: {
			development: {
				options: {
					paths: ["./css"],
					yuicompress: true
				},
			files: {
				"./css/portfolio.css": "./css/portfolio.less",
				"./css/resume.css": "./css/resume.less",
			}
		}
	},
	watch: {
		files: "./css/*.less",
		tasks: ["less"]
	}
});
	grunt.loadNpmTasks('grunt-contrib-less');
	grunt.loadNpmTasks('grunt-contrib-watch');
};
