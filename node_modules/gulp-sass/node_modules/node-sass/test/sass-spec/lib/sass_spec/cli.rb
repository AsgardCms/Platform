
module SassSpec::CLI
  require 'optparse'

  def self.parse
    options = {
      sass_executable: "sass",
      spec_directory: "spec",
      skip: false,
      verbose: false,

      # Constants
      input_file: 'input.scss',
      expected_file: 'expected_output.css',
      todo_path: '/todo/'
    }

    OptionParser.new do |opts|
      opts.banner = "Usage: ./sass-spec.rb [options]

Examples:
  Run `sassc --style compressed input.scss`:
  ./sass-spec.rb -c 'sass --style compressed'

  Run tests only in the spec/basic folder:
  ./sass-spec.rb spec/basic

This script will search for all files under the spec (or specified) directory
that are named input.scss. It will then run a specified binary and check that
the output matches the expected output. If you want set up your own test suite,
follow a similar hierarchy as described in the initial comment of this script
for your test hierarchy.

Make sure the command you provide prints to stdout.

"

      opts.on("-v", "--verbose", "Run verbosely") do
        options[:verbose] = true
      end

      opts.on("-c", "--command COMMAND", "Sets a specific binary to run (defaults to '#{options[:sass_executable]}')") do |v|
        options[:sass_executable] = v
      end

      opts.on("--ignore-todo", "Skip any folder named 'todo'") do
        options[:skip_todo] = true
      end

      opts.on("-s", "--skip", "Skip tests that fail to exit successfully") do
        options[:skip] = true
      end

      opts.on("--silent", "Don't show any logs") do
        options[:silent] = true
      end
    end.parse!

    options[:spec_directory] = ARGV[0] if !ARGV.empty?

    options
  end
end
