# bitexpert/etcetera
A small lightweight ETL framework

- [Scope](#scope)
- [How does it work?](#work)
- [Getting started](#gettingstarted)

## <a name="scope"></a>Scope
Etcetera may be used for several jobs concering reading data from a source (typically *.xlsx files) transforming the data
to a desired format by using converters, validators, filters and decorators and storing it to a desired target (e.g. MongoDB)

## <a name="work"></a>How does it work?
Etcetera consists of four main parts:
### Reader
The reader is used for reading data from your source
### Extractor
The extractor is where the magic happens. The extractor extracts the relevant data from the data read by the reader
and transforms it by rules described in it's configuration. The resulting data is called extract.
### Writer
Everytime a dataset is read and extracted, the writer receives the resulting extract and may write it to any target you want to.
### Processor
The processor the heart of etcetera, it's the glue between reader, extractor and writer

The reader has to read datasets from your desired source and has to create ValueDescriptors from every value found in the source.
The extractor will now extract and transform the data of a single dataset described by the ValueDescriptors according to your configured rules.
After having extracted and transformed the data will be passed as extract to your writer.

For further instructions please have a look at [bitexpert/etcetera-demo](https://github.com/bitexpert/etcetera-demo)

Further docs will follow as soon as possible.
